<?php
// FROM HASH: 2d7afb5da59be9c362a3dec11adc8e74
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['movies'], 'empty', array())) {
		$__compilerTemp1 .= '
		';
		$__templater->includeCss('carousel.less');
		$__compilerTemp1 .= '
		';
		$__templater->includeCss('lightslider.less');
		$__compilerTemp1 .= '
		';
		$__templater->includeCss('snog_movies_slider.less');
		$__compilerTemp1 .= '

		';
		$__templater->includeJs(array(
			'prod' => 'xf/carousel-compiled.js',
			'dev' => 'vendor/lightslider/lightslider.min.js',
		));
		$__compilerTemp1 .= '
		';
		$__templater->includeJs(array(
			'dev' => 'Snog/Movies/slider.js',
			'prod' => 'Snog/Movies/slider.min.js',
			'addon' => 'Snog/Movies',
		));
		$__compilerTemp1 .= '

		<div class="carousel carousel-moviePosterSlider carousel--withFooter" ' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
			<ul class="carousel-body" data-xf-init="snog-movies-slider"
				data-xf-snog-movies-slider="' . $__templater->filter($__vars['options']['slider'], array(array('json', array()),), true) . '">

				';
		if ($__templater->isTraversable($__vars['movies'])) {
			foreach ($__vars['movies'] AS $__vars['movie']) {
				$__compilerTemp1 .= '
					<li>
						<div class="carousel-item-simple">
							<a class="carousel-item-image" href="' . $__templater->func('link', array('threads', $__vars['movie']['Thread'], ), true) . '">
								<img src="' . $__templater->escape($__templater->method($__vars['movie'], 'getImageUrl', array())) . '" style="width: ' . $__templater->escape($__vars['options']['image_width']) . '; height: ' . $__templater->escape($__vars['options']['image_height']) . '" />

								';
				if ($__vars['options']['show_rating']) {
					$__compilerTemp1 .= '
									<span class="carousel-item-label">
										' . $__templater->fontAwesome('star', array(
					)) . ' ' . $__templater->escape($__vars['movie']['tmdb_rating']) . '
									</span>
								';
				}
				$__compilerTemp1 .= '
								
								<span class="carousel-item-caption" title="' . $__templater->escape($__vars['movie']['Thread']['title']) . '">
									' . $__templater->escape($__vars['movie']['Thread']['title']) . '
								</span>
							</a>
						</div>
					</li>
				';
			}
		}
		$__compilerTemp1 .= '
			</ul>

		</div>
	';
	}
	$__vars['template'] = $__templater->preEscaped('
	' . $__compilerTemp1 . '
');
	$__finalCompiled .= '

';
	if (!$__vars['options']['advanced_mode']) {
		$__finalCompiled .= '
	';
		$__compilerTemp2 = '';
		$__compilerTemp2 .= $__templater->filter($__vars['template'], array(array('raw', array()),), true);
		if (strlen(trim($__compilerTemp2)) > 0) {
			$__finalCompiled .= '
	<div class="block">
		<div class="block-container" ' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
			';
			if ($__vars['title']) {
				$__finalCompiled .= '
				<h3 class="block-minorHeader">' . $__templater->escape($__vars['title']) . '</h3>
			';
			}
			$__finalCompiled .= '
			<div class="block-body block-row">
				' . $__compilerTemp2 . '
			</div>
		</div>
	</div>
	';
		}
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	' . $__templater->filter($__vars['template'], array(array('raw', array()),), true) . '
';
	}
	return $__finalCompiled;
}
);