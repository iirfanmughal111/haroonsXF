<?php

namespace Snog\TV\Service\TVForum;

use Snog\TV\Entity\TVForum;
use XF\Service\ValidateAndSavableTrait;

class Create extends \XF\Service\AbstractService
{
	/**
	 * @var \Snog\TV\XF\Entity\Node
	 */
	protected $newNode;

	/**
	 * @var \Snog\TV\XF\Entity\Forum
	 */
	protected $newForum;

	/**
	 * @var TVForum
	 */
	protected $tvForum;

	/**
	 * @var \Snog\TV\XF\Entity\Node
	 */
	protected $parentNode;


	use ValidateAndSavableTrait;

	public function __construct(\XF\App $app, \XF\Entity\Node $parentNode, $tvId)
	{
		parent::__construct($app);

		$this->parentNode = $parentNode;

		$this->newNode = $this->setupNewNode();
		$this->tvForum = $this->setupTvForum($tvId);
		$this->newForum = $this->tvForum->getNewForum();
	}

	public function getTvForum()
	{
		return $this->tvForum;
	}

	protected function setupNewNode()
	{
		/** @var \XF\Entity\Node $newNode */
		$newNode = $this->em()->create('XF:Node');
		$newNode->node_type_id = 'Forum';
		$newNode->parent_node_id = $this->parentNode->node_id;

		return $newNode;
	}

	public function setNewNodeDisplayOrder($displayOrder)
	{
		$this->newNode->display_order = $displayOrder;
	}

	protected function setupTvForum($tvId)
	{
		$tvForum = $this->newNode->getNewTv();
		$tvForum->tv_id = $tvId;
		$tvForum->tv_parent = $this->parentNode->node_id ?? 0;
		$tvForum->tv_parent_id = $this->parentNode->TVForum->tv_id ?? 0;

		return $tvForum;
	}

	public function setShowTitle($title)
	{
		$title = substr($title, 0, 50);

		$this->tvForum->tv_title = $title;
		$this->newNode->title = $title;
	}

	public function setTvGenres($genres)
	{
		$this->tvForum->tv_genres = $genres;
	}

	public function setTvDirectors($directors)
	{
		$this->tvForum->tv_director = $directors;
	}

	public function setTvCast($cast)
	{
		$this->tvForum->tv_cast = $cast;
	}

	public function setSeason($season)
	{
		$this->tvForum->tv_season = $season;
	}

	public function setTvImage($imagePath)
	{
		$this->tvForum->tv_image = $imagePath;
	}

	public function setTvRelease($releaseDate)
	{
		$this->tvForum->tv_release = $releaseDate;
	}

	public function setTvPlot($plot)
	{
		$this->tvForum->tv_plot = $plot;
	}

	protected function afterInsert()
	{
	}

	protected function _validate()
	{
		$newNode = $this->newNode;
		$newNode->preSave();

		return $newNode->getErrors();
	}

	protected function _save()
	{
		$newNode = $this->newNode;
		$tvForum = $this->tvForum;

		$newNode->save();

		/** @var \XF\Repository\PermissionCombination $combinationRepo */
		$combinationRepo = $this->repository('XF:PermissionCombination');
		$combination = $combinationRepo->updatePermissionCombinationForUser(\XF::visitor(), false);
		$this->app->permissionBuilder()->rebuildCombination($combination);

		/** @var Image $imageService */
		$imageService = $this->app->service('Snog\TV:TVForum\Image', $tvForum);
		$imageService->setImageFromApiPath($tvForum->tv_image, $this->app->options()->TvThreads_largePosterSize);
		$imageService->updateImage();

		if ($this->tvForum->tv_season)
		{
			/** @var \XF\Entity\Node[] $nodeSort */
			$nodeSort = $this->finder('XF:Node')
				->where('parent_node_id', $this->parentNode->node_id)
				->order('TVForum.tv_season', $this->app->options()->TvThreads_sort)
				->fetch();
		}
		else
		{
			/** @var \XF\Entity\Node[] $nodeSort */
			$nodeSort = $this->finder('XF:Node')
				->where('parent_node_id', $this->parentNode->node_id)
				->order('title')
				->fetch();
		}

		$order = 100;

		foreach ($nodeSort as $node)
		{
			$node->display_order = $order;
			$node->save();
			$order = $order + 100;
		}

		return $newNode;
	}
}