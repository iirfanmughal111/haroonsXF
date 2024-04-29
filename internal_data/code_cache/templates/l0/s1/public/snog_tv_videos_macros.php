<?php
// FROM HASH: b7bdd94c9a0ec0631db285c66139691d
return array(
'macros' => array('video_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'tv' => '!',
		'videos' => '!',
		'page' => '1',
		'hasMore' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<ul class="listPlain js-tvVideoList' . $__templater->escape($__vars['tv']['tv_id']) . '">
		';
	if ($__templater->isTraversable($__vars['videos'])) {
		foreach ($__vars['videos'] AS $__vars['video']) {
			$__finalCompiled .= '
			<li class="block-row block-row--separated">
				' . $__templater->callMacro(null, 'video_info', array(
				'video' => $__vars['video'],
			), $__vars) . '
			</li>
		';
		}
	}
	$__finalCompiled .= '
	</ul>

	';
	if ($__vars['hasMore']) {
		$__finalCompiled .= '
		<div class="block-footer js-tvVideoLoadMore' . $__templater->escape($__vars['tv']['tv_id']) . '">
			<span class="block-footer-controls">
				' . $__templater->button('
					' . 'More' . $__vars['xf']['language']['ellipsis'] . '
				', array(
			'href' => $__templater->func('link', array('tv/videos', $__vars['tv'], array('page' => $__vars['page'] + 1, ), ), false),
			'data-xf-click' => 'inserter',
			'data-append' => '.js-tvVideoList' . $__vars['tv']['tv_id'],
			'data-replace' => '.js-tvVideoLoadMore' . $__vars['tv']['tv_id'],
		), '', array(
		)) . '
			</span>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'video_info' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'video' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="contentRow">
		<div class="contentRow-figure">
			';
	if ($__vars['video']['site'] == 'YouTube') {
		$__finalCompiled .= '
				<iframe width="300" src="https://www.youtube.com/embed/' . $__templater->escape($__vars['video']['key']) . '?wmode=opaque&showinfo=0&controls=2&start=0"
						height="150"
						allowfullscreen
						frameborder="0">
				</iframe>
			';
	}
	$__finalCompiled .= '
		</div>
		<div class="contentRow-main">
			<h2 class="contentRow-title">' . $__templater->escape($__vars['video']['name']) . '</h2>
			<div class="contentRow-minor">
				<ul class="listInline listInline--bullet">
					<li>' . $__templater->escape($__vars['video']['type']) . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['video']['published_at'], array(
	))) . '</li>
				</ul>
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);