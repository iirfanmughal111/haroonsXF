<?php

namespace Snog\Movies\Widget;


class Slider extends \XF\Widget\AbstractWidget
{
	protected $defaultOptions = [
		'order' => 'latest',
		'limit' => 12,
		'node_ids' => [],

		'show_plot' => true,
		'show_genres' => true,
		'show_director' => true,
		'show_cast' => true,
		'show_status' => true,
		'show_release_date' => true,
		'show_runtime' => true,

		'slider' => [
			'auto' => false,
			'pause' => '4000',
			'pauseOnHover' => false,
			'loop' => false,
			'controls' => false,
			'pager' => false
		]
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

			'show_plot' => 'bool',
			'show_genres' => 'bool',
			'show_director' => 'bool',
			'show_cast' => 'bool',
			'show_status' => 'bool',
			'show_release_date' => 'bool',
			'show_runtime' => 'bool',

			'slider' => [
				'auto' => 'bool',
				'pause' => 'uint',
				'pauseOnHover' => 'bool',
				'loop' => 'bool',
				'controls' => 'bool',
				'pager' => 'bool'
			]
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

		$movieFinder = $this->app->finder('Snog\Movies:Movie')
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
			$movieFinder->order('tmdb_rating', 'DESC');
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
		return $this->renderer('widget_snog_movies_slider', $viewParams);
	}
}