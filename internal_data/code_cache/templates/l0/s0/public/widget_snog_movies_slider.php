<?php
// FROM HASH: 60abfca21f2a58785c743443bff2eeb8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['movies'], 'empty', array())) {
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
			'dev' => 'Snog/Movies/slider.js',
			'prod' => 'Snog/Movies/slider.min.js',
			'addon' => 'Snog/Movies',
		));
		$__finalCompiled .= '

	<div class="carousel carousel--withFooter" ' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
		<ul class="carousel-body carousel-body--show2" data-xf-init="snog-movies-slider"
			data-xf-snog-movies-slider="' . $__templater->filter($__vars['options']['slider'], array(array('json', array()),), true) . '">
			';
		if ($__templater->isTraversable($__vars['movies'])) {
			foreach ($__vars['movies'] AS $__vars['movie']) {
				$__finalCompiled .= '
				<li>
					<div class="carousel-item">
						<div class="contentRow">
							<div class="contentRow-figure">
								<span class="contentRow-figureIcon"><img src="' . $__templater->escape($__templater->method($__vars['movie'], 'getImageUrl', array())) . '" /></span>
							</div>

							<div class="contentRow-main">
								<div class="contentRow-title">
									<a href="' . $__templater->func('link', array('threads', $__vars['movie']['Thread'], ), true) . '">' . $__templater->escape($__vars['movie']['Thread']['title']) . '</a>
									<span class="label">' . $__templater->fontAwesome('star', array(
				)) . ' ' . $__templater->escape($__vars['movie']['tmdb_rating']) . '</span>
								</div>

								';
				if ($__vars['options']['show_plot'] AND $__vars['movie']['tmdb_plot']) {
					$__finalCompiled .= '
									<div class="contentRow-lesser">
										' . $__templater->func('snippet', array($__vars['movie']['tmdb_plot'], 150, ), true) . '
									</div>
								';
				}
				$__finalCompiled .= '

								<div class="contentRow-minor contentRow-minor--smaller">
									<ul class="listInline listInline--bullet">
										';
				if ($__vars['options']['show_tagline'] AND $__vars['movie']['tmdb_tagline']) {
					$__finalCompiled .= '<li><b>' . 'Tagline' . ':</b> ' . $__templater->escape($__vars['movie']['tmdb_tagline']) . '</li>';
				}
				$__finalCompiled .= '
										';
				if ($__vars['options']['show_genres'] AND $__vars['movie']['tmdb_genres']) {
					$__finalCompiled .= '<li><b>' . 'Genre' . ':</b> ' . $__templater->escape($__vars['movie']['tmdb_genres']) . '</li>';
				}
				$__finalCompiled .= '
										';
				if ($__vars['options']['show_director'] AND $__vars['movie']['tmdb_director']) {
					$__finalCompiled .= '<li><b>' . 'Director' . ':</b> ' . $__templater->escape($__vars['movie']['tmdb_director']) . '</li>';
				}
				$__finalCompiled .= '
										';
				if ($__vars['options']['show_cast'] AND $__vars['movie']['tmdb_cast']) {
					$__finalCompiled .= '<li><b>' . 'Cast' . ':</b> ' . $__templater->escape($__vars['movie']['tmdb_cast']) . '</li>';
				}
				$__finalCompiled .= '
										';
				if ($__vars['options']['show_status'] AND $__vars['movie']['tmdb_status']) {
					$__finalCompiled .= '<li><b>' . 'Status' . ':</b> ' . $__templater->escape($__vars['movie']['tmdb_status']) . '</li>';
				}
				$__finalCompiled .= '
										';
				if ($__vars['options']['show_release_date'] AND $__vars['movie']['tmdb_release']) {
					$__finalCompiled .= '<li><b>' . 'Release' . ':</b> ' . $__templater->escape($__vars['movie']['tmdb_release']) . '</li>';
				}
				$__finalCompiled .= '
										';
				if ($__vars['options']['show_runtime'] AND $__vars['movie']['tmdb_runtime']) {
					$__finalCompiled .= '<li><b>' . 'Runtime' . ':</b> ' . $__templater->escape($__vars['movie']['tmdb_runtime']) . '</li>';
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