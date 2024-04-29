<?php

namespace XFMG\Pub\View\Rss;

class Index extends \XF\Mvc\View
{
	public function renderRss()
	{
		$app = \XF::app();
		$router = $app->router('public');
		$options = $app->options();

		$title = strval($this->params['feedTitle']);
		$description = strval($this->params['feedDescription']);
		$link = $this->params['feedLink'];

		$feed = new \Zend\Feed\Writer\Feed();

		$feed->setEncoding('utf-8')
			->setTitle($title)
			->setDescription($description)
			->setLink($router->buildLink('canonical:index'))
			->setFeedLink($link, 'rss')
			->setDateModified(\XF::$time)
			->setLastBuildDate(\XF::$time)
			->setGenerator($options->boardTitle);

		/** @var \XFMG\Entity\MediaItem $mediaItem */
		foreach ($this->params['mediaItems'] AS $mediaItem)
		{
			$entry = $feed->createEntry();

			$entry->setTitle($mediaItem->title ?: \XF::phrase('xfmg_media_item')->render());

			if ($mediaItem->description)
			{
				$entry->setDescription($mediaItem->description);
			}

			$entry->setLink($router->buildLink('canonical:media', $mediaItem))
				->setDateCreated($mediaItem->media_date)
				->setDateModified($mediaItem->last_edit_date);

			if ($mediaItem->category_id && $mediaItem->Category)
			{
				$entry->addCategory([
					'term' => $mediaItem->Category->title,
					'scheme' => $router->buildLink('canonical:media/categories', $mediaItem->Category)
				]);
			}
			if ($mediaItem->album_id && $mediaItem->Album)
			{
				$entry->addCategory([
					'term' => $mediaItem->Album->title,
					'scheme' => $router->buildLink('canonical:media/albums', $mediaItem->Album)
				]);
			}

			$content = $this->renderer->getTemplater()->renderTemplate('public:xfmg_rss_content', [
				'mediaItem' => $mediaItem
			]);

			$entry->setContent($content);

			$entry->addAuthor([
				'name' => $mediaItem->username ?: strval(\XF::phrase('guest')),
				'email' => 'invalid@example.com',
				'uri' => $router->buildLink('canonical:members', $mediaItem)
			]);
			if ($mediaItem->comment_count)
			{
				$entry->setCommentCount($mediaItem->comment_count);
			}

			$feed->addEntry($entry);
		}

		return $feed->export('rss', true);
	}
}