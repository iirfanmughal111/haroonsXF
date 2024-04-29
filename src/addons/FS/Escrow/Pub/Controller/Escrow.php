<?php

namespace FS\Escrow\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;
use XF\Mvc\RouteMatch;

// use FS\Escrow\BarCode\phpqrcode;

include __DIR__ . '/../../BarCode/phpqrcode/qrlib.php';

class Escrow extends AbstractController
{

    public function actionIndex(ParameterBag $params)
    {

        if (!file_exists(\XF::getRootDirectory() . '/data/Barcode')) {
            // Create the folder if it doesn't exist
            mkdir(\XF::getRootDirectory() . '/data/Barcode', 0777, true);
        }

        $text = "GEEKS FOR GEEKS";

        // $path variable store the location where to
        // store image and $file creates directory name
        // of the QR code file by using 'uniqid'
        // uniqid creates unique id based on microtime
        // $path = __FILE__ . 'Barcode/' . uniqid() . '.png';

        $fileName =  uniqid() . '.png';
        $path = \XF::getRootDirectory() . '/data/Barcode/' . $fileName;

        // $file = $path . uniqid() . ".png";

        // var_dump($path);
        // $ecc stores error correction capability('L')
        $ecc = 'L';
        $pixel_Size = 10;
        $frame_Size = 10;

        // Generates QR Code and Stores it in directory given
        \QRcode::png($text, $path, $ecc, $pixel_Size, $frame_Size);

        $file = \XF::app()->applyExternalDataUrl('Barcode/' . $fileName, true);
        // Displaying the stored QR code from directory
        echo "<center><img src='" . $file . "'></center>";
        exit;
        $visitor = \XF::visitor();
        $rules[] = [
            'message' => \XF::phrase('fs_escrow_rules'),
            "display_image" => "avatar",
            "display_style" => "primary",

        ];

        if ($this->filter('search', 'uint') || $this->filterSearchConditions()) {
            $finder = $this->getSearchFinder();
        } else {
            $finder = $this->finder('FS\Escrow:Escrow')->where('thread_id', '!=', 0)->whereOr([['to_user', $visitor->user_id], ['user_id' => $visitor->user_id]]);
        }


        $page = $this->filterPage($params->page);
        $perPage = 15;
        $finder->limitByPage($page, $perPage);
        $viewpParams = [
            'rules' => $rules,
            'page' => $page,
            'total' => $finder->total(),
            'perPage' => $perPage,
            'stats' => $this->auctionStatistics(),
            'escrowsCount' => $this->auctionEscrowCount(),
            'escrows' => $finder->order('last_update', 'desc')->fetch(),
            'conditions' => $this->filterSearchConditions(),
            'isSelected' => $this->filter(['type' => 'str']),

        ];


        return $this->view('FS\Escrow', 'fs_escrow_landing', $viewpParams);
    }

    protected function getSearchFinder()
    {
        $visitor = \XF::visitor();
        $conditions = $this->filterSearchConditions();
        $finder = $this->finder('FS\Escrow:Escrow')->where('thread_id', '!=', 0);
        $search = 0;
        if ($conditions['fs_escrow_mentioned_username'] != '') {

            $User = $this->finder('XF:User')->where('username', $conditions['fs_escrow_mentioned_username'])->fetchOne();
            if ($User) {
                $finder->where('user_id', $User['user_id']);
                $finder->where('to_user', $visitor->user_id);
                $search = 1;
            }
        }
        if ($conditions['fs_escrow_status'] != 'all') {
            if (intval($conditions['fs_escrow_status']) > 0 && intval($conditions['fs_escrow_status']) <= 5) {
                $finder->whereOr([['to_user', $visitor->user_id], ['user_id' => $visitor->user_id]]);
                $finder->where('escrow_status', (intval($conditions['fs_escrow_status']) - 1));
                $search = 1;
            }
        }

        if (isset($conditions['type']) && $conditions['type'] != '') {
            if ($conditions['type'] == 'my') {
                $finder->where('user_id', $visitor->user_id);
                $search = 1;
            } else if ($conditions['type'] == 'mentioned') {
                $finder->where('to_user', $visitor->user_id);
                $search = 1;
            }
        }
        if (!$search) {
            $finder->whereOr([['to_user', $visitor->user_id], ['user_id' => $visitor->user_id]]);
        }

        return $finder;
    }


    public function filterPage($page = 0, $inputName = 'page')
    {
        return max(1, intval($page) ?: $this->filter($inputName, 'uint'));
    }

    public function actionRefineSearch(ParameterBag $params)
    {

        $viewParams = [
            'conditions' => $this->filterSearchConditions(),
        ];
        return $this->view('FS\Escrow:Escrow', 'fs_escrow_public_search_filter', $viewParams);
    }

    protected function filterSearchConditions()
    {
        $filters = $this->filter([
            'fs_escrow_status' => 'str',
            'fs_escrow_mentioned_username' => 'str',
            'type' => 'str'

        ]);
        //   if ($filters['type']=='my'){
        //         $filters['isSelected'] = 'my';
        //     }
        //     else if ($filters['type']=='mentioned'){
        //         $filters['isSelected'] = 'mentioned';
        //     }
        return $filters;
    }
    protected function auctionEscrowCount()
    {
        $visitor = \XF::visitor();

        $finder = $this->finder('FS\Escrow:Escrow')->whereOr([['to_user', $visitor->user_id], ['user_id' => $visitor->user_id]])->where('thread_id', '!=', 0);
        $finderMentioned = clone $finder;
        $finderMy = clone $finder;



        return [
            'all' => [
                'title' => \XF::phrase('fs_escrow_all'),
                'type' => 'all',
                'count' => $finder->total(),
            ],

            'my_escrow' => [
                'title' => \XF::phrase('fs_my_escrow'),
                'type' => 'my',
                'count' => $finderMy->where('user_id', $visitor->user_id)->total(),
            ],

            'mentioned_escrow' => [
                'title' => \XF::phrase('fs_mentioned_escrow'),
                'type' => 'mentioned',
                'count' => $finderMentioned->where('to_user', $visitor->user_id)->total(),
            ],



        ];
    }

    protected function auctionStatistics()
    {
        $visitor = \XF::visitor();

        $finder = $this->finder('FS\Escrow:Escrow')->whereOr([['to_user', $visitor->user_id], ['user_id' => $visitor->user_id]])->where('thread_id', '!=', 0);
        $finder0 = clone $finder;
        $finder1 = clone $finder;
        $finder2 = clone $finder;
        $finder3 = clone $finder;
        $finder4 = clone $finder;

        return [

            'status_0' => [
                'title' => \XF::phrase('fs_escrow_status_0'),
                'count' => $finder0->where('escrow_status', 0)->total(),
            ],

            'status_1' => [
                'title' => \XF::phrase('fs_escrow_status_1'),
                'count' => $finder1->where('escrow_status', 1)->total(),
            ],

            'status_2' => [
                'title' => \XF::phrase('fs_escrow_status_2'),
                'count' => $finder2->where('escrow_status', 2)->total(),
            ],

            'status_3' => [
                'title' => \XF::phrase('fs_escrow_status_3'),
                'count' => $finder3->where('escrow_status', 3)->total(),
            ],

            'status_4' => [
                'title' => \XF::phrase('fs_escrow_status_4'),
                'count' => $finder4->where('escrow_status', 4)->total(),
            ],

        ];
    }


    public function actionAdd(ParameterBag $params)
    {

        $options = $this->app()->options();

        $forum = $this->finder('XF:Forum')->where('node_id', intval($options->fs_escrow_applicable_forum))->fetchOne();

        return $this->redirect($this->buildLink('forums/post-thread', $forum));

        $viewpParams = [];
        return $this->view('FS\Escrow', 'fs_escrow_addEdit', $viewpParams);
    }

    public function actionDeposit(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        if ($visitor->user_id == 0) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }

        $viewpParams = [
            'pageSelected' => 'escrow/deposit',
        ];
        return $this->view('FS\Escrow', 'fs_escrow_deposit', $viewpParams);
    }

    public function actionDepositSave(ParameterBag $params)
    {
        $this->insertTransaction();

        return $this->redirect(
            // $this->getDynamicRedirect($this->buildLink('escrow/deposit'), false)
            $this->getDynamicRedirect()
        );
    }

    protected function insertTransaction()
    {
        $inputs = $this->filterDepositeAmountInputs();

        $visitor = \XF::visitor();

        $visitor->fastUpdate('deposit_amount', ($visitor->deposit_amount + $inputs['deposit_amount']));

        $escrowService = \xf::app()->service('FS\Escrow:Escrow\EscrowServ');

        $escrowService->escrowTransaction($visitor->user_id, $inputs['deposit_amount'], $visitor->deposit_amount, 'Deposit', 0);

        return true;
    }

    protected function filterDepositeAmountInputs()
    {
        $input = $this->filter([
            'deposit_amount' => 'int',
        ]);

        if ($input['deposit_amount'] > 0) {
            return $input;
        }

        throw $this->exception(
            $this->notFound(\XF::phrase("fs_escrow_amount_required"))
        );
    }

    public function actionMyEscrow(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        if ($visitor->user_id == 0) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }

        $escrows = $this->finder('FS\Escrow:Escrow')->where('user_id', $visitor->user_id);

        $viewpParams = [
            'escrows' => $escrows->fetch(),
        ];
        return $this->view('FS\Escrow', 'fs_escrow_escrow_list', $viewpParams);
    }

    public function actionMentionedEscrow(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        if ($visitor->user_id == 0) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }

        $escrows = $this->finder('FS\Escrow:Escrow')->where('to_user', $visitor->user_id);

        $viewpParams = [
            'escrows' => $escrows->fetch(),


        ];
        return $this->view('FS\Escrow', 'fs_escrow_escrow_list', $viewpParams);
    }

    public function actionLogs(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        if ($visitor->user_id == 0) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }
        $page = $this->filterPage();
        $perPage = 2;
        $logs = $this->finder('FS\Escrow:Transaction')->where('user_id', $visitor->user_id);
        // $logs->limitByPage($page, $perPage);
        $viewpParams = [

            'logs' => $logs->fetch(),
            // 'page'=>$page,
            // 'perPage'=>$perPage,
            // 'total' => $logs->total(),

        ];
        return $this->view('FS\Escrow', 'fs_escrow_logs', $viewpParams);
    }

    public function actionCancel(ParameterBag $params)
    {
        $escrow = $this->assertDataExists($params->escrow_id);

        if ($this->isPost()) {

            $this->cancelEscrow($escrow);

            return $this->redirect(
                $this->getDynamicRedirect($this->buildLink('escrow'), $escrow->Thread)
            );
        } else {

            $viewParams = [
                'escrow' => $escrow,
            ];
            return $this->view('FS\Escrow:Escrow\Cancel', 'fs_escrow_cancel', $viewParams);
        }
    }


    public function actionApprove(ParameterBag $params)
    {
        $escrow = $this->assertDataExists($params->escrow_id);

        if ($this->isPost()) {

            $this->approveEscrow($escrow);

            /** @var Escrow $notifier */
            $notifier = $this->app->notifier('FS\Escrow:Listing\EscrowAlert', $escrow);
            $notifier->escrowApproveAlert();

            return $this->redirect(
                $this->getDynamicRedirect($this->buildLink('escrow'), $escrow->Thread)
            );
        } else {

            $viewParams = [
                'escrow' => $escrow,
            ];
            return $this->view('FS\Escrow:Escrow\Approve', 'fs_escrow_approve', $viewParams);
        }
    }

    public function actionPayments(ParameterBag $params)
    {
        $escrow = $this->assertDataExists($params->escrow_id);

        // /** @var \XF\ControllerPlugin\Delete $plugin */
        // $plugin = $this->plugin('XF:Delete');

        if ($this->isPost()) {

            $this->paymentEscrow($escrow);

            /** @var Escrow $notifier */
            $notifier = $this->app->notifier('FS\Escrow:Listing\EscrowAlert', $escrow);
            $notifier->escrowPaymentAlert();

            return $this->redirect(
                $this->getDynamicRedirect($this->buildLink('escrow'), $escrow->Thread)
            );
        } else {

            $viewParams = [
                'escrow' => $escrow,
            ];
            return $this->view('FS\Escrow:Escrow\Payments', 'fs_escrow_payment', $viewParams);
        }
    }

    protected function cancelEscrow($escrow)
    {
        $visitor = \XF::visitor();

        if ($escrow->user_id != $visitor->user_id && $escrow->to_user != $visitor->user_id) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }

        if ($escrow->user_id != $visitor->user_id) {
            $visitor = $this->em()->findOne('XF:User', ['user_id' => $escrow->user_id]);
        }

        if ($escrow) {

            $escrow->Thread->User->fastUpdate('deposit_amount', ($escrow->Thread->User->deposit_amount + $escrow->Transaction->transaction_amount));

            $escrowService = \xf::app()->service('FS\Escrow:Escrow\EscrowServ');

            $escrowService->escrowTransaction($escrow->Thread->User->user_id, $escrow->Transaction->transaction_amount, $escrow->Thread->User->deposit_amount, 'Cancel', $escrow->escrow_id);

            $visitor = \XF::visitor();

            if ($escrow->user_id == $visitor->user_id) {
                $escrow->bulkSet([
                    'escrow_status' => '3',
                    'last_update' => \XF::$time,
                ]);

                /** @var Escrow $notifier */
                $notifier = $this->app->notifier('FS\Escrow:Listing\EscrowAlert', $escrow);
                $notifier->escrowCancelByOwnerAlert();
            } else {
                $escrow->bulkSet([
                    'escrow_status' => '2',
                    'last_update' => \XF::$time,
                ]);
                /** @var Escrow $notifier */
                $notifier = $this->app->notifier('FS\Escrow:Listing\EscrowAlert', $escrow);
                $notifier->escrowCancelAlert();
            }

            $escrow->save();
            // $escrow->fastUpdate('last_update', \XF::$time);
        }
    }

    protected function approveEscrow($escrow)
    {
        $visitor = \XF::visitor();

        if ($escrow->to_user != $visitor->user_id) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }

        $escrow->bulkSet([
            'escrow_status' => '1',
            'last_update' => \XF::$time,
        ]);
        $escrow->save();
    }

    protected function paymentEscrow($escrow)
    {
        $visitor = \XF::visitor();

        if ($escrow->user_id != $visitor->user_id) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }
        // $user = $this->em()->findOne('XF:User', ['user_id' => $escrow->to_user]);

        $escrow->User->fastUpdate('deposit_amount', ($escrow->User->deposit_amount + $escrow->escrow_amount));

        $escrowService = \xf::app()->service('FS\Escrow:Escrow\EscrowServ');

        $escrowService->escrowTransaction($escrow->User->user_id, $escrow->escrow_amount, $escrow->User->deposit_amount, 'Payment', $escrow->escrow_id);

        $percentageUser = $this->em()->findOne('XF:User', ['user_id' => intval($this->app()->options()->fs_escrow_admin_Id)]);

        if ($percentageUser) {

            $escrowPercentage = $percentageUser->deposit_amount + (($escrow->admin_percentage / 100) * $escrow->escrow_amount);

            $percentageUser->fastUpdate('deposit_amount', $escrowPercentage);

            $escrowService->escrowTransaction($percentageUser->user_id, (($escrow->admin_percentage / 100) * $escrow->escrow_amount), $percentageUser->deposit_amount, 'Percentage', $escrow->escrow_id);

            /** @var Escrow $notifier */
            $notifier = $this->app->notifier('FS\Escrow:Listing\EscrowAlert', $escrow);
            $notifier->escrowPercentageHolderUserAlert();
        }

        $escrow->bulkSet([
            'escrow_status' => '4',
            'last_update' => \XF::$time,
        ]);
        $escrow->save();
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \FS\Escrow\Entity\Escrow
     */
    protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
    {
        return $this->assertRecordExists('FS\Escrow:Escrow', $id, $extraWith, $phraseKey);
    }
}
