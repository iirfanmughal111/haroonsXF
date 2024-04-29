<?php
// FROM HASH: b66575d2354618d6f5303adab9719705
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['tvShows'], 'empty', array())) {
		$__finalCompiled .= '

	';
		$__templater->includeCss('carousel.less');
		$__finalCompiled .= '
	';
		$__templater->includeCss('lightslider.less');
		$__finalCompiled .= '
	';
		$__templater->includeJs(array(
			'prod' => 'xf/carousel-compiled.js',
			'dev' => 'vendor/lightslider/lightslider.min.js, xf/carousel.js',
		));
		$__finalCompiled .= '
	';
		$__templater->includeJs(array(
			'dev' => 'Snog/TV/slider.js',
			'prod' => 'Snog/TV/slider.min.js',
			'addon' => 'Snog/TV',
		));
		$__finalCompiled .= '

	<div class="carousel carousel--withFooter" ' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
		<ul class="carousel-body carousel-body--show2" data-xf-init="snog-tv-slider"
			data-xf-snog-tv-slider="' . $__templater->filter($__vars['options']['slider'], array(array('json', array()),), true) . '">
			';
		if ($__templater->isTraversable($__vars['tvShows'])) {
			foreach ($__vars['tvShows'] AS $__vars['tv']) {
				$__finalCompiled .= '
				<li>
					<div class="carousel-item">
						<div class="contentRow">
							<div class="contentRow-figure">
								<span class="contentRow-figureIcon"><img src="' . $__templater->escape($__templater->method($__vars['tv'], 'getImageUrl', array())) . '" /></span>
							</div>

							<div class="contentRow-main">
								<div class="contentRow-title">
									<a href="' . $__templater->func('link', array('threads', $__vars['tv']['Thread'], ), true) . '">' . $__templater->escape($__vars['tv']['Thread']['title']) . '</a>
									<span class="label">' . $__templater->fontAwesome('star', array(
				)) . ' ' . $__templater->escape($__vars['tv']['tv_rating']) . '</span>
								</div>

								';
				if ($__vars['options']['show_plot'] AND $__vars['tv']['tv_plot']) {
					$__finalCompiled .= '
									<div class="contentRow-lesser">
										' . $__templater->func('snippet', array($__vars['tv']['tv_plot'], 150, ), true) . '
									</div>
								';
				}
				$__finalCompiled .= '

								<div class="contentRow-minor contentRow-minor--smaller">
									<ul class="listInline listInline--bullet">
										';
				if ($__vars['options']['show_genres'] AND $__vars['tv']['tv_genres']) {
					$__finalCompiled .= '<li><b>' . 'Genre' . ':</b> ' . $__templater->escape($__vars['tv']['tv_genres']) . '</li>';
				}
				$__finalCompiled .= '
										';
				if ($__vars['options']['show_director'] AND $__vars['tv']['tv_director']) {
					$__finalCompiled .= '<li><b>' . 'Director' . ':</b> ' . $__templater->escape($__vars['tv']['tv_director']) . '</li>';
				}
				$__finalCompiled .= '
										';
				if ($__vars['options']['show_cast'] AND $__vars['tv']['tv_cast']) {
					$__finalCompiled .= '<li><b>' . 'Cast' . ':</b> ' . $__templater->escape($__vars['tv']['tv_cast']) . '</li>';
				}
				$__finalCompiled .= '
										';
				if ($__vars['options']['show_release'] AND $__vars['tv']['first_air_date']) {
					$__finalCompiled .= '<li><b>' . 'First aired' . ':</b> ' . $__templater->func('date_dynamic', array($__vars['tv']['first_air_date'], array(
					))) . '</li>';
				}
				$__finalCompiled .= '
										';
				if ($__vars['options']['show_last_air_date'] AND $__vars['tv']['last_air_date']) {
					$__finalCompiled .= '<li><b>' . 'Last air date' . ':</b> ' . $__templater->func('date_dynamic', array($__vars['tv']['last_air_date'], array(
					))) . '</li>';
				}
				$__finalCompiled .= '
										';
				if ($__vars['options']['show_status'] AND !$__templater->test($__vars['tv']['status'], 'empty', array())) {
					$__finalCompiled .= '<li><b>' . 'Show status' . ':</b> ' . $__templater->escape($__vars['tv']['status']) . '</li>';
				}
				$__finalCompiled .= '
									</ul>
								</div>
							</div>
						</div>
					</div>
				</li>
			';
			}
		}
		$__finalCompiled .= '
		</ul>
	</div>
';
	}
	return $__finalCompiled;
}
);