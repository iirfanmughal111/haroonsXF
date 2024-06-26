<?php

namespace nick97\TraktMovies\Widget;


class PosterSlider extends \XF\Widget\AbstractWidget
{
	protected $defaultOptions = [
		'order' => 'latest',
		'limit' => 12,
		'node_ids' => [],

		'image_width' => '185px',
		'image_height' => '278px',
		'show_rating' => false,

		'slider' => [
			'items' => 6,
			'auto' => false,
			'pause' => 4000,
			'controls' => false,

			'pauseOnHover' => false,
			'loop' => false,
			'pager' => false,
			'itemsWide' => 4,
			'breakpointWide' => 900,
			'itemsMedium' => 2,
			'breakpointMedium' => 480,
		],
		'advanced_mode' => true,
	];

	protected function setupOptions(array $options)
	{
		return array_replace_recursive($this->defaultOptions, $options);
	}

	protected function getDefaultTemplateParams($context)
	{
		$params = parent::getDefaultTemplateParams($context);
		if ($context == 'options')
		{
			/** @var \XF\Repository\Node $nodeRepo */
			$nodeRepo = $this->app->repository('XF:Node');
			$params['nodeTree'] = $nodeRepo->createNodeTree($nodeRepo->getFullNodeList());
		}

		return $params;
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'order' => 'str',
			'limit' => 'uint',
			'node_ids' => 'array-uint',
			'advanced_mode' => 'bool',

			'image_width' => 'str',
			'image_height' => 'str',
			'show_rating' => 'bool',

			'slider' => [
				'items' => 'uint',
				'auto' => 'bool',
				'pause' => 'uint',
				'controls' => 'bool',
				'pauseOnHover' => 'bool',
				'loop' => 'bool',
				'pager' => 'bool',

				'itemsWide' => 'uint',
				'breakpointWide' => 'uint',
				'itemsMedium' => 'uint',
				'breakpointMedium' => 'uint',
			],
		]);
		if (in_array(0, $options['node_ids']))
		{
			$options['node_ids'] = [0];
		}
		if ($options['limit'] < 1)
		{
			$options['limit'] = 1;
		}

		return true;
	}

	public function render()
	{
		$options = $this->options;

		$movieFinder = $this->app->finder('nick97\TraktMovies:Movie')
			->with('Thread', true)
			->where('Thread.discussion_state', '=', 'visible');

		if (!empty($options['node_ids']) && !in_array(0, $options['node_ids']))
		{
			$movieFinder->where('Thread.node_id', $options['node_ids']);
		}

		if ($this->options['order'] == 'random')
		{
			$movieFinder->orderRandom(\XF::$time);
		}
		elseif ($this->options['order'] == 'rating')
		{
			$movieFinder->order('trakt_rating', 'DESC');
		}
		elseif ($this->options['order'] == 'last_post_date')
		{
			$movieFinder->order('Thread.last_post_date', 'DESC');
		}
		else
		{
			$movieFinder->order('Thread.post_date', 'DESC');
		}

		$movies = $movieFinder->limit($options['limit'])->fetch();

		$viewParams = [
			'movies' => $movies,
			'sliderOptions' => $options['slider'],
			'options' => $options,
			'title' => $this->getTitle(),
		];
		return $this->renderer('widget_trakt_movie_poster_slider', $viewParams);
	}
}