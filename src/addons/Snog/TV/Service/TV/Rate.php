<?php

namespace Snog\TV\Service\TV;

class Rate extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \Snog\TV\Entity\TV
	 */
	protected $tv;

	/**
	 * @var \Snog\TV\Entity\Rating
	 */
	protected $rating;

	/**
	 * @var \XF\Entity\User
	 */
	protected $user;

	public function __construct(\XF\App $app, \Snog\TV\Entity\TV $tv)
	{
		parent::__construct($app);
		$this->tv = $tv;

		$this->setUser(\XF::visitor());
		$this->rating = $this->setupRating();
	}

	public function setUser(\XF\Entity\User $user)
	{
		$this->user = $user;
	}

	protected function setupRating()
	{
		/** @var \Snog\TV\Entity\Rating $rating */
		$rating = $this->em()->create('Snog\TV:Rating');

		$rating->thread_id = $this->tv->thread_id;
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

		$existing = $this->tv->Ratings[$this->user->user_id] ?? null;
		if ($existing)
		{
			$existing->delete();
		}

		$rating->save(true, false);

		$this->afterInsert();

		$db->commit();

		return $rating;
	}
}