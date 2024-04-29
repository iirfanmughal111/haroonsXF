<?php

namespace nick97\TraktTV\Service\TVForum;

class Rate extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \nick97\TraktTV\Entity\TVForum
	 */
	protected $tvForum;

	/**
	 * @var \nick97\TraktTV\Entity\RatingNode
	 */
	protected $rating;

	/**
	 * @var \XF\Entity\User
	 */
	protected $user;

	public function __construct(\XF\App $app, \nick97\TraktTV\Entity\TVForum $tvForum)
	{
		parent::__construct($app);
		$this->tvForum = $tvForum;

		$this->setUser(\XF::visitor());
		$this->rating = $this->setupRating();
	}

	public function setUser(\XF\Entity\User $user)
	{
		$this->user = $user;
	}

	protected function setupRating()
	{
		/** @var \nick97\TraktTV\Entity\RatingNode $rating */
		$rating = $this->em()->create('nick97\TraktTV:RatingNode');

		$rating->node_id = $this->tvForum->node_id;
		$rating->user_id = $this->user->user_id;

		return $rating;
	}

	public function setRating($rating)
	{
		$this->rating->rating = $rating;
	}

	protected function finalSetup()
	{
	}

	protected function _validate()
	{
		$rating = $this->rating;
		$this->finalSetup();

		$rating->preSave();
		return $rating->getErrors();
	}

	protected function afterInsert()
	{
	}

	protected function _save()
	{
		$rating = $this->rating;

		$db = $this->db();
		$db->beginTransaction();

		$existing = $this->tvForum->Ratings[$this->user->user_id] ?? null;
		if ($existing) {
			$existing->delete();
		}

		$rating->save(true, false);

		$this->afterInsert();

		$db->commit();

		return $rating;
	}
}
