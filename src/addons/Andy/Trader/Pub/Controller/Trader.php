<?php

namespace Andy\Trader\Pub\Controller;

use XF\Pub\Controller\AbstractController;

class Trader extends AbstractController
{
	public function actionHistory()
	{
		//########################################
		// history
		//########################################
		
		// get visitor
		$visitor = \XF::visitor();				
		
		// check for user group permission
		if (!$visitor->hasPermission('trader', 'view'))
		{
			return $this->noPermission();
		}
		
		// get userId
		$userId = $this->filter('user_id', 'uint');
        
        // get user
        $finder = \XF::finder('XF:User');
        $user = $finder
            ->where('user_id', $userId)
            ->fetchOne();
		
		// check condition
		if (empty($user))
		{
			return $this->error(\XF::phrase('requested_user_not_found'));
		}
		
		// get username
		$username = $user['username'];

		// get results
		$finder = \XF::finder('Andy\Trader:Trader');
		$results = $finder
			->where('seller_id', $userId)
			->where('buyer_comment', '<>', '')
			->fetch();

		// get sellerCount
		$sellerCount = count($results);

		// get results
		$finder = \XF::finder('Andy\Trader:Trader');
		$results = $finder
			->where('buyer_id', $userId)
			->where('seller_comment', '<>', '')
			->fetch();

		// get buyerCount
		$buyerCount = count($results);			

		// get viewParams
		$viewParams = [
			'user' => $user,
			'userId' => $userId,
			'username' => $username,
			'sellerCount' => $sellerCount,
			'buyerCount' => $buyerCount
		];				

		// send to template
		return $this->view('Andy\Trader:History', 'andy_trader_history', $viewParams);
	}	
	
	public function actionRateSeller()
	{
		//########################################
		// rate seller
		//########################################
				
		// get visitor
		$visitor = \XF::visitor();				
		
		// check for user group permission
		if (!$visitor->hasPermission('trader', 'rate'))
		{
			return $this->noPermission();
		}				
		
		// get userId
		$userId = $this->filter('user_id', 'uint');	
		
		// get buyer_id
		$buyer_id = $visitor['user_id'];
		
		// prevent self rating 
		if ($userId == $buyer_id)
		{
			// return error
			return $this->error(\XF::phrase('trader_php_cannot_rate_oneself'));
		}
        
        // get result
        $finder = \XF::finder('XF:User');
        $result = $finder
            ->where('user_id', $userId)
            ->fetchOne();
		
		// check condition
		if (empty($result))
		{
			// return error
			return $this->error(\XF::phrase('error'));
		}
		
		// get user
		$user = array(
			'user_id' => $result['user_id'],
			'username' => $result['username']
		);

        // get username
        $username = $result['username'];			
		
		// prepare viewParams
		$viewParams = [
			'user' => $user,
			'userId' => $userId,
			'username' => $username
		];			
						
		// send to template
		return $this->view('Andy\Trader:RateSeller', 'andy_trader_rate_seller', $viewParams);
	}
	
	public function actionRateBuyer()
	{
		//########################################
		// rate buyer
		//########################################
				
		// get visitor
		$visitor = \XF::visitor();				
		
		// check for user group permission
		if (!$visitor->hasPermission('trader', 'rate'))
		{
			return $this->noPermission();
		}		
		
		// get userId
		$userId = $this->filter('user_id', 'uint');		
		
		// get seller_id
		$seller_id = $visitor['user_id'];
		
		// prevent self rating 
		if ($userId == $seller_id)
		{
			// return error
			return $this->error(\XF::phrase('trader_php_cannot_rate_oneself'));
		}
        
        // get result
        $finder = \XF::finder('XF:User');
        $result = $finder
            ->where('user_id', $userId)
            ->fetchOne();
		
		// check condition
		if (empty($result))
		{
			// return error
			return $this->error(\XF::phrase('error'));
		}
		
		// get user
		$user = array(
			'user_id' => $result['user_id'],
			'username' => $result['username']
		);

        // get username
        $username = $result['username'];			
		
		// prepare viewParams
		$viewParams = [
			'user' => $user,
			'userId' => $userId,
			'username' => $username
		];			
						
		// send to template
		return $this->view('Andy\Trader:RateBuyer', 'andy_trader_rate_buyer', $viewParams);
	}
	
	public function actionRatingSeller()
	{
		//########################################
		// rating seller
		//########################################
				
		// get visitor
		$visitor = \XF::visitor();				
		
		// check for user group permission
		if (!$visitor->hasPermission('trader', 'view'))
		{
			return $this->noPermission();
		}
		
		// get options
		$options = \XF::options();		
		
		// get options from Admin CP -> Options -> Trader -> Results limit
		$resultsLimit = $options->traderResultsLimit;	
		
		// get options from Admin CP -> Options -> Trader -> Edit limit
		$editLimit = $options->traderEditLimit;			
		
		// define editLimit
		$editLimit = time() - ($editLimit * 60);	
		
		// get userId
		$userId = $this->filter('user_id', 'uint');
        
        // get result
        $finder = \XF::finder('XF:User');
        $result = $finder
            ->where('user_id', $userId)
            ->fetchOne();
		
		// check condition
		if (empty($result))
		{
			// return error
			return $this->error(\XF::phrase('error'));
		}
		
		// get user
		$user = array(
			'user_id' => $result['user_id'],
			'username' => $result['username']
		);

        // get results
        $finder = \XF::finder('Andy\Trader:Trader');
        $results = $finder
            ->where('seller_id', $userId) 
            ->where('buyer_comment', '<>', '')
            ->order('timestamp', 'DESC')
            ->limit($resultsLimit)
            ->fetch();

		// prepare viewParams
		$viewParams = [
			'user' => $user,
			'userId' => $userId,
			'results' => $results,
			'editLimit' => $editLimit
		];			
						
		// send to template
		return $this->view('Andy\Trader:RatingSeller', 'andy_trader_rating_seller', $viewParams);
	}
	
	public function actionRatingBuyer()
	{
		//########################################
		// rating buyer
		//########################################
				
		// get visitor
		$visitor = \XF::visitor();				
		
		// check for user group permission
		if (!$visitor->hasPermission('trader', 'view'))
		{
			return $this->noPermission();
		}
		
		// get options
		$options = \XF::options();		
		
		// get options from Admin CP -> Options -> Trader -> Results limit
		$resultsLimit = $options->traderResultsLimit;	
		
		// get options from Admin CP -> Options -> Trader -> Edit Limit
		$editLimit = $options->traderEditLimit;			
		
		// define editLimit
		$editLimit = time() - ($editLimit * 60);			
		
		// get userId
		$userId = $this->filter('user_id', 'uint');
        
        // get result
        $finder = \XF::finder('XF:User');
        $result = $finder
            ->where('user_id', $userId)
            ->fetchOne();
		
		// check condition
		if (empty($result))
		{
			// return error
			return $this->error(\XF::phrase('error'));
		}
		
		// get user
		$user = array(
			'user_id' => $result['user_id'],
			'username' => $result['username']
		);	
        
        // get results
        $finder = \XF::finder('Andy\Trader:Trader');
        $results = $finder
            ->where('buyer_id', $userId) 
            ->where('seller_comment', '<>', '')
            ->order('timestamp', 'DESC')
            ->limit($resultsLimit)
            ->fetch();	
		
		// prepare viewParams
		$viewParams = [
			'user' => $user,
			'userId' => $userId,
			'results' => $results,
			'editLimit' => $editLimit
		];			
						
		// send to template
		return $this->view('Andy\Trader:RatingBuyer', 'andy_trader_rating_buyer', $viewParams);
	}	
	
	public function actionEditSeller()
	{
		//########################################
		// edit seller
		//########################################
		
		// get visitor
		$visitor = \XF::visitor();				
		
		// check for user group permission
		if (!$visitor->hasPermission('trader', 'rate'))
		{
			return $this->noPermission();
		}
		
		// get options
		$options = \XF::options();		
		
		// get options from Admin CP -> Options -> Trader -> Edit limit
		$editLimit = $options->traderEditLimit;		
		
		// get traderId
		$traderId = $this->filter('trader_id', 'uint');
        
        // get result
        $finder = \XF::finder('Andy\Trader:Trader');
        $results = $finder
            ->where('trader_id', $traderId)
            ->fetchOne();
		
		// get options from Admin CP -> Options -> Trader -> Edit limit
		$editLimit = $options->traderEditLimit;
		
		// define variable
		$timeNow = time();
		
		// continue after checks 
		if (!$visitor->hasPermission('trader', 'admin'))
		{		
			if ($results['buyer_id'] != $visitor['user_id'])
			{
				// return error
				return $this->error(\XF::phrase('error'));
			}			

			if ($results['timestamp'] < ($timeNow - ($editLimit * 60)))
			{
				// return error
				return $this->error(\XF::phrase('trader_php_edit_time_limit_expired'));
			}
		}
		
		// define variables
		$checked1 = '';
		$checked2 = '';
		$checked3 = '';
		
		if ($results['rating'] == '0')
		{
			$checked1 = 'checked';
		}
		
		if ($results['rating'] == '1')
		{
			$checked2 = 'checked';
		}
		
		if ($results['rating'] == '2')
		{
			$checked3 = 'checked';
		}
		
		// define username
		$username = $results->UserSeller['username'];						
		
		// prepare viewParams
		$viewParams = [
			'results' => $results,
			'checked1' => $checked1,
			'checked2' => $checked2,
			'checked3' => $checked3,
			'traderId' => $traderId,
			'username' => $username
		];					

		// send to template
		return $this->view('Andy\Trader:EditSeller', 'andy_trader_edit_seller', $viewParams);
	}
	
	public function actionEditBuyer()
	{
		//########################################
		// edit buyer
		//########################################
		
		// get visitor
		$visitor = \XF::visitor();				
		
		// check for user group permission
		if (!$visitor->hasPermission('trader', 'rate'))
		{
			return $this->noPermission();
		}
		
		// get options
		$options = \XF::options();
		
		// get options from Admin CP -> Options -> Trader -> Edit limit
		$editLimit = $options->traderEditLimit;		
		
		// get traderId
		$traderId = $this->filter('trader_id', 'uint');
        
        // get result
        $finder = \XF::finder('Andy\Trader:Trader');
        $results = $finder
            ->where('trader_id', $traderId)
            ->fetchOne();
		
		// get options from Admin CP -> Options -> Trader -> Edit Limit
		$editLimit = $options->traderEditLimit;
		
		// define variable
		$timeNow = time();
		
		// continue after checks 
		if (!$visitor->hasPermission('trader', 'admin'))
		{
			if ($results['seller_id'] != $visitor['user_id'])
			{
				// return error
				return $this->error(\XF::phrase('error'));			
			}			

			if ($results['timestamp'] < ($timeNow - ($editLimit * 60)))
			{
				// return error
				return $this->error(\XF::phrase('trader_php_edit_time_limit_expired'));			
			}
		}
		
		// define variables
		$checked1 = '';
		$checked2 = '';
		$checked3 = '';
		
		if ($results['rating'] == '0')
		{
			$checked1 = 'checked';
		}
		
		if ($results['rating'] == '1')
		{
			$checked2 = 'checked';
		}
		
		if ($results['rating'] == '2')
		{
			$checked3 = 'checked';
		}
		
		// define username
		$username = $results->UserBuyer['username'];			
		
		// prepare viewParams
		$viewParams = [
			'results' => $results,
			'checked1' => $checked1,
			'checked2' => $checked2,
			'checked3' => $checked3,
			'traderId' => $traderId,
			'username' => $username
		];					

		// send to template
		return $this->view('Andy\Trader:EditBuyer', 'andy_trader_edit_buyer', $viewParams);
	}	
	
	public function actionDeleteSeller()
	{
		//########################################
		// delete seller
		//########################################
			
		// get visitor
		$visitor = \XF::visitor();				
		
		// check for user group permission
		if (!$visitor->hasPermission('trader', 'admin'))
		{
			return $this->noPermission();
		}
		
		// get traderId
		$traderId = $this->filter('trader_id', 'uint');
        
        // get result
        $finder = \XF::finder('Andy\Trader:Trader');
        $result = $finder
            ->where('trader_id', $traderId)
            ->fetchOne();
		
		// check condition
		if (empty($result))
		{
			// return error
			return $this->error(\XF::phrase('error'));
		}

        // get sellerId
        $sellerId = $result['seller_id'];

        // get results
        $finder = \XF::finder('Andy\Trader:Trader');
        $results = $finder
            ->where('trader_id', $traderId)
            ->fetch();

        // foreach condition
        foreach ($results AS $result)
        {
            $result->delete();
        }
		
        // get results
        $finder = \XF::finder('Andy\Trader:Trader');
        $results = $finder
            ->where('seller_id', $sellerId)
            ->where('buyer_comment', '<>', '')
            ->fetch();   

        // get sellerCount
        $sellerCount = count($results);

        // get results
        $finder = \XF::finder('XF:User');
        $results = $finder
            ->where('user_id', $sellerId)
            ->fetch();

        // foreach condition
        foreach ($results as $result)
        {
            $data = [
                'andy_trader_seller_count' => $sellerCount
            ];

            $result->fastUpdate($data);
        }		

		// return to ratingseller
		return $this->redirect($this->buildLink('trader/ratingseller', '', array('user_id' => $sellerId)));
	}
	
	public function actionDeleteBuyer()
	{
		//########################################
		// delete buyer
		//########################################		
			
		// get visitor
		$visitor = \XF::visitor();				
		
		// check for user group permission
		if (!$visitor->hasPermission('trader', 'admin'))
		{
			return $this->noPermission();
		}

		// get traderId
		$traderId = $this->filter('trader_id', 'uint');		

        // get result
        $finder = \XF::finder('Andy\Trader:Trader');
        $result = $finder
            ->where('trader_id', $traderId)
            ->fetchOne();
		
		// check condition
		if (empty($result))
		{
			// return error
			return $this->error(\XF::phrase('error'));
		}

        // get buyerId
        $buyerId = $result['buyer_id'];

        // get results
        $finder = \XF::finder('Andy\Trader:Trader');
        $results = $finder
            ->where('trader_id', $traderId)
            ->fetch();

        // foreach condition
        foreach ($results AS $result)
        {
            $result->delete();
        }
		
        // get results
        $finder = \XF::finder('Andy\Trader:Trader');
        $results = $finder
            ->where('buyer_id', $buyerId)
            ->where('seller_comment', '<>', '')
            ->fetch();   

        // get buyerCount
        $buyerCount = count($results);

        // get results
        $finder = \XF::finder('XF:User');
        $results = $finder
            ->where('user_id', $buyerId)
            ->fetch();

        // foreach condition
        foreach ($results as $result)
        {
            $data = [
                'andy_trader_buyer_count' => $buyerCount
            ];

            $result->fastUpdate($data);
        }

		// return to ratingbuyer
		return $this->redirect($this->buildLink('trader/ratingbuyer', '', array('user_id' => $buyerId)));
	}		
	
	public function actionSaveSeller()
	{
		//########################################
		// save seller
		//########################################	
				
		// get visitor
		$visitor = \XF::visitor();				
		
		// check for user group permission
		if (!$visitor->hasPermission('trader', 'rate'))
		{
			return $this->noPermission();
		}
		
		// assert post only
		$this->assertPostOnly();		
		
		// get userId
		$userId = $this->filter('user_id', 'uint');		

		// get username 
		$username = $this->filter('username', 'str');
		
		// verify username
		if ($username == '')
		{
			return $this->error(\XF::phrase('trader_php_username_missing'));
		}	
        
        // get user
        $finder = \XF::finder('XF:User');
        $user = $finder
            ->where('username', $username)
            ->fetchOne();		
		
		// get rating 
		$rating = $this->filter('rating', 'str');
		
		// make sure a rating was selected
		if ($rating == '')
		{
			return $this->error(\XF::phrase('trader_php_rating_missing'));
		}
		
		// get buyer_comment
		$buyer_comment = $this->filter('buyer_comment', 'str');

		// make sure a buyer comment was made
		if ($buyer_comment == '')
		{
			return $this->error(\XF::phrase('trader_php_buyer_comment_missing'));
		}
		
		// get options
		$options = \XF::options();		
		
		// get options from Admin CP -> Options -> Trader -> Multibyte
		$multibyte = $options->traderMultibyte;	
		
		if (!$multibyte)
		{
			// limit to 200 characters
			$buyer_comment = substr($buyer_comment, 0, 200);
		}
		
		if ($multibyte)
		{
			// limit to 200 characters
			$buyer_comment = mb_substr($buyer_comment, 0, 200);
		}

		// get timestamp
		$timestamp = time();
		
		// get buyer_id
		$buyer_id = $visitor['user_id'];
        
        // get result
        $finder = \XF::finder('XF:User');
        $result = $finder
            ->where('user_id', $buyer_id)
            ->fetchOne();
		
		// check condition
		if (empty($result))
		{
			// return error
			return $this->error(\XF::phrase('error'));
		}
        
        // buyerName
        $buyerName = $result['username'];		
		
		// define variable
		$seller_comment = '';

        // save trader
        $trader = \XF::em()->create('Andy\Trader:Trader');
        $trader->timestamp = $timestamp;
        $trader->rating = $rating;
        $trader->seller_id = $userId;
        $trader->buyer_id = $buyer_id;
        $trader->seller_comment = $seller_comment;
        $trader->buyer_comment = $buyer_comment;
        $trader->save();
        
        // get results
        $finder = \XF::finder('Andy\Trader:Trader');
        $results = $finder
            ->where('seller_id', $userId)
            ->where('buyer_comment', '<>', '')
            ->fetch();   

        // get sellerCount
        $sellerCount = count($results);

        // get results
        $finder = \XF::finder('XF:User');
        $results = $finder
            ->where('user_id', $userId)
            ->fetch();

        // foreach condition
        foreach ($results as $result)
        {
            $data = [
                'andy_trader_seller_count' => $sellerCount
            ];

            $result->fastUpdate($data);
        }
		
        // get seller
        $finder = \XF::finder('XF:User');
        $seller = $finder
            ->where('user_id', $userId)
            ->fetchOne();
		
        // get buyer
        $finder = \XF::finder('XF:User');
        $buyer = $finder
            ->where('user_id', $buyer_id)
            ->fetchOne();
		
		// get email_address
		$email_address = $user['email'];
		
		// check condition
		if (!empty($email_address))
		{
			// send email
			$mail = \XF::app()->mailer()->newMail();

			$user = \XF::app()->find('XF:User', $userId);
			$mail->setToUser($user);

			$mail->setTemplate('andy_trader_seller', [
				'seller' => $seller,
				'buyer' => $buyer,
				'rating' => $rating,
				'buyer_comment' => $buyer_comment
			]);

			$mail->queue();
		}
		
		// return to history
		return $this->redirect($this->buildLink('trader/history', '', array('user_id' => $userId)));
	}
	
	public function actionSaveBuyer()
	{
		//########################################
		// save buyer
		//########################################	
				
		// get visitor
		$visitor = \XF::visitor();				
		
		// check for user group permission
		if (!$visitor->hasPermission('trader', 'rate'))
		{
			return $this->noPermission();
		}
		
		// make sure data comes from $_POST
		$this->assertPostOnly();	
		
		// get userId
		$userId = $this->filter('user_id', 'uint');					
		
		// get username
		$username = $this->filter('username', 'str');
		
		// verify username
		if ($username == '')
		{
			return $this->error(\XF::phrase('trader_php_username_missing'));
		}
        
        // get user
        $finder = \XF::finder('XF:User');
        $user = $finder
            ->where('username', $username)
            ->fetchOne();					
		
		// get rating 
		$rating = $this->filter('rating', 'str');
		
		// make sure a rating was selected
		if ($rating == '')
		{
			return $this->error(\XF::phrase('trader_php_rating_missing'));
		}					
		
		// get seller_comment 
		$seller_comment = $this->filter('seller_comment', 'str');
		
		// make sure a seller comment was made
		if ($seller_comment == '')
		{
			return $this->error(\XF::phrase('trader_php_seller_comment_missing'));
		}
		
		// get options
		$options = \XF::options();		
		
		// get options from Admin CP -> Options -> Trader -> Multibyte
		$multibyte = $options->traderMultibyte;	
		
		if (!$multibyte)
		{
			// limit to 200 characters
			$seller_comment = substr($seller_comment, 0, 200);
		}
		
		if ($multibyte)
		{
			// limit to 200 characters
			$seller_comment = mb_substr($seller_comment, 0, 200);
		}		

		// get timestamp
		$timestamp = time();
		
		// get seller_id
		$seller_id = $visitor['user_id'];

        // get result
        $finder = \XF::finder('XF:User');
        $result = $finder
            ->where('user_id', $seller_id)
            ->fetchOne();
		
		// check condition
		if (empty($result))
		{
			// return error
			return $this->error(\XF::phrase('error'));
		}
        
        // sellerName
        $sellerName = $result['username'];	
		
		// define variable
		$buyer_comment = '';
        
        // save trader
        $trader = \XF::em()->create('Andy\Trader:Trader');
        $trader->timestamp = $timestamp;
        $trader->rating = $rating;
        $trader->seller_id = $seller_id;
        $trader->buyer_id = $userId;
        $trader->seller_comment = $seller_comment;
        $trader->buyer_comment = $buyer_comment;
        $trader->save();

        // get results
        $finder = \XF::finder('Andy\Trader:Trader');
        $results = $finder
            ->where('buyer_id', $userId)
            ->where('seller_comment', '<>', '')
            ->fetch();   

        // get buyerCount
        $buyerCount = count($results);

        // get results
        $finder = \XF::finder('XF:User');
        $results = $finder
            ->where('user_id', $userId)
            ->fetch();

        // foreach condition
        foreach ($results as $result)
        {
            $data = [
                'andy_trader_buyer_count' => $buyerCount
            ];

            $result->fastUpdate($data);
        }
		
        // get buyer
        $finder = \XF::finder('XF:User');
        $buyer = $finder
            ->where('user_id', $userId)
            ->fetchOne();
		
        // get seller
        $finder = \XF::finder('XF:User');
        $seller = $finder
            ->where('user_id', $seller_id)
            ->fetchOne();
		
		// get email_address
		$email_address = $user['email'];

		// check condition
		if (!empty($email_address))
		{
			// get email_address
			$email_address = $user['email'];				

			// send email
			$mail = \XF::app()->mailer()->newMail();

			$user = \XF::app()->find('XF:User', $userId);
			$mail->setToUser($user);

			$mail->setTemplate('andy_trader_buyer', [
				'buyer' => $buyer,
				'seller' => $seller,
				'rating' => $rating,
				'seller_comment' => $seller_comment
			]);

			$mail->queue();
		}
		
		// return to history
		return $this->redirect($this->buildLink('trader/history', '', array('user_id' => $userId)));		
	}	
	
	public function actionUpdateSeller()
	{
		//########################################
		// update seller
		//########################################	

		// get visitor
		$visitor = \XF::visitor();				
		
		// check for user group permission
		if (!$visitor->hasPermission('trader', 'rate'))
		{
			return $this->noPermission();
		}
		
		// make sure data comes from $_POST
		$this->assertPostOnly();
				
		// get traderId 
		$traderId = $this->filter('trader_id', 'uint');
		
		// get username 
		$username = $this->filter('username', 'str');
		
		// verify username
		if ($username == '')
		{
			return $this->error(\XF::phrase('trader_php_username_missing'));
		}
        
        // get result
        $finder = \XF::finder('XF:User');
        $result = $finder
            ->where('username', $username)
            ->fetchOne();
		
		// check condition
		if (empty($result))
		{
			// return error
			return $this->error(\XF::phrase('error'));
		}
        
        // seller_id
        $seller_id = $result['user_id'];			
		
		// get rating 
		$rating = $this->filter('rating', 'str');
		
		// get buyer_comment 
		$buyer_comment = $this->filter('buyer_comment', 'str');
		
		// get options
		$options = \XF::options();		
		
		// get options from Admin CP -> Options -> Trader -> Multibyte
		$multibyte = $options->traderMultibyte;	
		
		if (!$multibyte)
		{
			// limit to 200 characters
			$buyer_comment = substr($buyer_comment, 0, 200);
		}
		
		if ($multibyte)
		{
			// limit to 200 characters
			$buyer_comment = mb_substr($buyer_comment, 0, 200);
		}
        
        // get results
        $finder = \XF::finder('Andy\Trader:Trader');
        $results = $finder
            ->where('trader_id', $traderId)
            ->fetch();

        // foreach condition
        foreach ($results as $result)
        {
            $data = [
                'rating' => $rating,
                'buyer_comment' => $buyer_comment
            ];

            $result->fastUpdate($data);
        }

		// return to ratingseller
		return $this->redirect($this->buildLink('trader/ratingseller', '', array('user_id' => $seller_id)));
	}
	
	public function actionUpdateBuyer()
	{
		//########################################
		// update buyer
		//########################################	

		// get visitor
		$visitor = \XF::visitor();				
		
		// check for user group permission
		if (!$visitor->hasPermission('trader', 'rate'))
		{
			return $this->noPermission();
		}
		
		// make sure data comes from $_POST
		$this->assertPostOnly();		
				
		// get traderId 
		$traderId = $this->filter('trader_id', 'uint');
	
		// get username 
		$username = $this->filter('username', 'str');				
		
		// verify name
		if ($username == '')
		{
			return $this->error(\XF::phrase('trader_php_username_missing'));
		}
        
        // get result
        $finder = \XF::finder('XF:User');
        $result = $finder
            ->where('username', $username)
            ->fetchOne();
		
		// check condition
		if (empty($result))
		{
			// return error
			return $this->error(\XF::phrase('error'));
		}
        
        // buyer_id
        $buyer_id = $result['user_id'];			
		
		// get rating 
		$rating = $this->filter('rating', 'str');
		
		// get seller_comment 
		$seller_comment = $this->filter('seller_comment', 'str');
		
		// get options
		$options = \XF::options();		
		
		// get options from Admin CP -> Options -> Trader -> Multibyte
		$multibyte = $options->traderMultibyte;	
		
		if (!$multibyte)
		{
			// limit to 200 characters
			$seller_comment = substr($seller_comment, 0, 200);
		}
		
		if ($multibyte)
		{
			// limit to 200 characters
			$seller_comment = mb_substr($seller_comment, 0, 200);
		}
        
        // get results
        $finder = \XF::finder('Andy\Trader:Trader');
        $results = $finder
            ->where('trader_id', $traderId)
            ->fetch();

        // foreach condition
        foreach ($results as $result)
        {
            $data = [
                'rating' => $rating,
                'seller_comment' => $seller_comment
            ];

            $result->fastUpdate($data);
        }

		// return to ratingbuyer
		return $this->redirect($this->buildLink('trader/ratingbuyer', '', array('user_id' => $buyer_id)));	
	}
}		