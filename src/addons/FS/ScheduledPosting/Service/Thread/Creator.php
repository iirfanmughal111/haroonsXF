<?php

namespace FS\ScheduledPosting\Service\Thread;

use XF\Entity\Forum;
use XF\Entity\Post;
use XF\Entity\Thread;
use XF\Entity\User;

class Creator extends XFCP_Creator
{
				protected $postUser=null;
    use \XF\Service\ValidateAndSavableTrait;

     public function __construct(\XF\App $app, Forum $forum,$user=null)
	{
	
	       $this->postUser = $user;
	       parent::__construct($app,$forum,$user);
	       
	        $this->forum = $forum;
		$this->setupDefaults();
		
		
	}
	
	protected function setupDefaults()
	{
	
		$this->thread = $this->forum->getNewThread();
		$this->post = $this->thread->getNewPost();

		$this->postPreparer = $this->service('XF:Post\Preparer', $this->post);

		$this->thread->addCascadedSave($this->post);
		$this->post->hydrateRelation('Thread', $this->thread);

		$this->tagChanger = $this->service('XF:Tag\Changer', 'thread', $this->forum);

		$user = \XF::visitor();
		$this->setUser($this->postUser ? $this->postUser :$user);

		$this->thread->discussion_state = $this->forum->getNewContentState();
		$this->post->message_state = 'visible';
	}
}