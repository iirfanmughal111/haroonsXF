<?php
// FROM HASH: 391ab2b39c6a0e628710b37b2a453db5
return array(
'macros' => array('user_trophy_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'trophy' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<li class="block-row block-row--separated">
		<div class="contentRow">
			<span class="contentRow-figure contentRow-figure--text contentRow-figure--fixedSmall">
				' . $__templater->callMacro(null, 'trophy', array(
		'trophy' => $__vars['trophy']['Trophy'],
		'extraCss' => $__vars['trophy']['Trophy']['th_icon_css'],
	), $__vars) . '
			</span>
			<div class="contentRow-main">
				<span class="contentRow-extra">' . $__templater->func('date_dynamic', array($__vars['trophy']['award_date'], array(
	))) . '</span>
				<h2 class="contentRow-header">
					<a href="' . $__templater->func('link', array('trophies', $__vars['trophy'], ), true) . '">' . $__templater->escape($__vars['trophy']['Trophy']['title']) . '</a>

					';
	if ($__vars['trophy']['Trophy']['trophy_points'] AND $__vars['xf']['options']['klUIShowPoints']) {
		$__finalCompiled .= '
						';
		if ($__vars['trophy']['Trophy']['trophy_points'] === 1) {
			$__finalCompiled .= '
							' . '(1 point)' . '
							';
		} else {
			$__finalCompiled .= '
							<span data-xf-init="tooltip" title="' . '(' . $__templater->filter($__vars['trophy']['Trophy']['trophy_points'], array(array('number', array()),), true) . ' points)' . '">
								' . '(' . $__templater->filter($__vars['trophy']['Trophy']['trophy_points'], array(array('number_short', array()),), true) . ' points)' . '
							</span>
						';
		}
		$__finalCompiled .= '
					';
	}
	$__finalCompiled .= '
				</h2>
				<div class="contentRow-minor">' . $__templater->filter($__vars['trophy']['Trophy']['description'], array(array('raw', array()),), true) . '</div>
			</div>
		</div>
	</li>
';
	return $__finalCompiled;
}
),
'trophy' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'trophy' => '!',
		'extraCss' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['trophy']['th_icon_type'] === 'fa') {
		$__finalCompiled .= '
		<i class="fa fa-' . $__templater->escape($__vars['trophy']['th_icon_value']) . ' ' . $__templater->escape($__vars['trophy']['th_icon_value']) . '" style="' . $__templater->escape($__vars['extraCss']) . '"></i>
	';
	} else if ($__vars['trophy']['th_icon_type'] === 'image') {
		$__finalCompiled .= '
		<img src="' . $__templater->escape($__vars['trophy']['th_icon_value']) . '" style="max-width: 60px; max-height: 38px;" style="' . $__templater->escape($__vars['extraCss']) . '" />
	';
	} else {
		$__finalCompiled .= '
		<span style="' . $__templater->escape($__vars['extraCss']) . '">' . $__templater->filter($__vars['trophy']['trophy_points'], array(array('number_short', array()),), true) . '</span>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'trophy_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'trophy' => '!',
		'showRecentlyAwardedUsers' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('thuserimprovements_help_page_trophies.less');
	$__finalCompiled .= '
	<li class="block-row block-row--separated trophy--' . ($__vars['trophy']['earned'] ? 'earned' : 'unearned') . '">
		<span id="trophy-' . $__templater->escape($__vars['trophy']['entity']['trophy_id']) . '"></span>
		<div class="contentRow ' . ((($__vars['depth'] % 100000) > 0) ? 'contentRow-child' : '') . '">
			<span class="contentRow-figure contentRow-figure--text contentRow-figure--fixedSmall">
				' . $__templater->callMacro(null, 'trophy', array(
		'trophy' => $__vars['trophy']['entity'],
		'extraCss' => $__vars['trophy']['entity']['th_icon_css'],
	), $__vars) . '
				';
	if ($__vars['trophy']['earned']) {
		$__finalCompiled .= '
					<span class="trophy--earned fa fa-check" data-xf-init="tooltip" title="' . 'Earned' . '"></span>
				';
	}
	$__finalCompiled .= '
			</span>
			<div class="contentRow-main">
				<h2 class="contentRow-header">
					';
	if ($__vars['trophy']['entity']['th_hidden'] AND (!$__vars['trophy']['earned'])) {
		$__finalCompiled .= '
						' . 'Hidden trophy' . '
						';
	} else {
		$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('trophies', $__vars['trophy']['entity'], ), true) . '">' . $__templater->escape($__vars['trophy']['entity']['title']) . '</a>
					';
	}
	$__finalCompiled .= '
					';
	if (($__vars['trophy']['max_level'] > 1) AND $__vars['xf']['options']['klUITrophyProgress']) {
		$__finalCompiled .= '
						' . '(' . $__templater->escape($__vars['trophy']['level']) . '/' . $__templater->escape($__vars['trophy']['max_level']) . ')' . '
					';
	}
	$__finalCompiled .= '

					';
	if ($__vars['trophy']['entity']['trophy_points'] AND $__vars['xf']['options']['klUIShowPoints']) {
		$__finalCompiled .= '
						';
		if ($__vars['trophy']['entity']['trophy_points'] === 1) {
			$__finalCompiled .= '
							' . '(1 point)' . '
							';
		} else {
			$__finalCompiled .= '
							<span data-xf-init="tooltip" title="' . '(' . $__templater->filter($__vars['trophy']['entity']['trophy_points'], array(array('number', array()),), true) . ' points)' . '">
								' . '(' . $__templater->filter($__vars['trophy']['entity']['trophy_points'], array(array('number_short', array()),), true) . ' points)' . '
							</span>
						';
		}
		$__finalCompiled .= '
					';
	}
	$__finalCompiled .= '
				</h2>
				<div class="contentRow-minor">
					';
	if ($__vars['trophy']['entity']['th_hidden'] AND (!$__vars['trophy']['earned'])) {
		$__finalCompiled .= '
						' . 'This trophy is hidden. Details are visible once it has been earned.' . '
						';
	} else {
		$__finalCompiled .= '
						' . $__templater->filter($__vars['trophy']['entity']['description'], array(array('raw', array()),), true) . '
					';
	}
	$__finalCompiled .= '
				</div>
				';
	if ($__vars['showRecentlyAwardedUsers']) {
		$__finalCompiled .= '
					<ul class="listHeap">
						';
		if ($__templater->isTraversable($__vars['trophy']['entity']['RecentlyAwardedUsers'])) {
			foreach ($__vars['trophy']['entity']['RecentlyAwardedUsers'] AS $__vars['user']) {
				$__finalCompiled .= '
							<li>
								' . $__templater->func('avatar', array($__vars['user']['User'], 's', false, array(
					'img' => 'true',
				))) . '
							</li>
						';
			}
		}
		$__finalCompiled .= '
					</ul>
				';
	}
	$__finalCompiled .= '
				<ol class="block-body">
					';
	if (($__vars['trophy']['level'] > 1) AND $__vars['xf']['options']['klUIFullTrophyProgress']) {
		$__finalCompiled .= '
						';
		if ($__templater->isTraversable($__vars['trophy']['predecessors'])) {
			foreach ($__vars['trophy']['predecessors'] AS $__vars['pred']) {
				$__finalCompiled .= '
							' . $__templater->callMacro(null, 'small_trophy', array(
					'trophy' => $__vars['pred'],
					'earned' => true,
					'showRecentlyAwardedUsers' => $__vars['showRecentlyAwardedUsers'],
				), $__vars) . '
						';
			}
		}
		$__finalCompiled .= '
					';
	}
	$__finalCompiled .= '
					';
	if (($__vars['trophy']['level'] > 1) AND ($__vars['xf']['options']['klUIFullTrophyProgress'] AND (($__vars['trophy']['max_level'] > $__vars['trophy']['level']) AND $__vars['xf']['options']['klUITrophyFollowers']))) {
		$__finalCompiled .= '
						' . $__templater->callMacro(null, 'small_trophy', array(
			'trophy' => $__vars['trophy']['entity'],
			'current' => true,
			'earned' => $__vars['trophy']['earned'],
			'showRecentlyAwardedUsers' => $__vars['showRecentlyAwardedUsers'],
		), $__vars) . '
					';
	}
	$__finalCompiled .= '
					';
	if (($__vars['trophy']['max_level'] > $__vars['trophy']['level']) AND $__vars['xf']['options']['klUITrophyFollowers']) {
		$__finalCompiled .= '
						';
		if ($__templater->isTraversable($__vars['trophy']['followers'])) {
			foreach ($__vars['trophy']['followers'] AS $__vars['foll']) {
				$__finalCompiled .= '
							' . $__templater->callMacro(null, 'small_trophy', array(
					'trophy' => $__vars['foll'],
					'showRecentlyAwardedUsers' => $__vars['showRecentlyAwardedUsers'],
				), $__vars) . '
						';
			}
		}
		$__finalCompiled .= '
					';
	}
	$__finalCompiled .= '
				</ol>
			</div>
		</div>
	</li>
';
	return $__finalCompiled;
}
),
'small_trophy' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'trophy' => '!',
		'current' => false,
		'earned' => false,
		'showRecentlyAwardedUsers' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<li class="block-row block-row--separated' . ($__vars['current'] ? ' trophy--current' : '') . ($__vars['earned'] ? ' trophy--earned' : '') . '">
		<h3 class="contentRow-header">
			';
	if ((!$__vars['earned']) AND $__vars['trophy']->{'th_hidden'}) {
		$__finalCompiled .= '
				' . 'Hidden trophy' . '
				';
	} else {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('trophies', $__vars['trophy'], ), true) . '">' . $__templater->escape($__vars['trophy']['title']) . '</a>
			';
	}
	$__finalCompiled .= '

			';
	if ($__vars['trophy']['trophy_points'] AND $__vars['xf']['options']['klUIShowPoints']) {
		$__finalCompiled .= '
				';
		if ($__vars['trophy']['trophy_points'] === 1) {
			$__finalCompiled .= '
					<span data-xf-init="tooltip" title="' . '(1 point)' . '">
					' . '(1 point)' . '
					</span>
					';
		} else {
			$__finalCompiled .= '
					<span data-xf-init="tooltip" title="' . '(' . $__templater->filter($__vars['trophy']['trophy_points'], array(array('number', array()),), true) . ' points)' . '">
					' . '(' . $__templater->filter($__vars['trophy']['trophy_points'], array(array('number_short', array()),), true) . ' points)' . '
					</span>
				';
		}
		$__finalCompiled .= '
			';
	}
	$__finalCompiled .= '

			';
	if ($__vars['earned']) {
		$__finalCompiled .= '
				<span class="trophy--earned fa fa-check" data-xf-init="tooltip" title="' . 'Earned' . '"></span>
			';
	}
	$__finalCompiled .= '
		</h3>

		<div class="contentRow-minor">
			';
	if ($__vars['trophy']['th_hidden'] AND (!$__vars['earned'])) {
		$__finalCompiled .= '
				' . 'This trophy is hidden. Details are visible once it has been earned.' . '
				';
	} else {
		$__finalCompiled .= '
				' . $__templater->filter($__vars['trophy']['description'], array(array('raw', array()),), true) . '
			';
	}
	$__finalCompiled .= '
		</div>

		';
	if ($__vars['showRecentlyAwardedUsers']) {
		$__finalCompiled .= '
			<ul class="listHeap">
				';
		if ($__templater->isTraversable($__vars['trophy']['RecentlyAwardedUsers'])) {
			foreach ($__vars['trophy']['RecentlyAwardedUsers'] AS $__vars['user']) {
				$__finalCompiled .= '
					<li>
						' . $__templater->func('avatar', array($__vars['user']['User'], 'xs', false, array(
					'img' => 'true',
				))) . '
					</li>
				';
			}
		}
		$__finalCompiled .= '
			</ul>
		';
	}
	$__finalCompiled .= '
	</li>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);