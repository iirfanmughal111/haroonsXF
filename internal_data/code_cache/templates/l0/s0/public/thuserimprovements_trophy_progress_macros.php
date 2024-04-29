<?php
// FROM HASH: cb21beb54446186d90722077fa20652a
return array(
'macros' => array('trophy_progress' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'trophyProgressCriteria' => '!',
		'trophies' => '!',
		'selectedTrophy' => '',
		'progressValue' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['trophyProgressCriteria']) {
		$__finalCompiled .= '
		<div class="block">
			<div class="block-container">
				<div class="block-body block-row">
					<ul class="th-trophyProgressBarContainer">
						';
		if ($__templater->isTraversable($__vars['trophies'])) {
			foreach ($__vars['trophies'] AS $__vars['trophy']) {
				$__finalCompiled .= '
							' . $__templater->callMacro(null, 'trophy_progress_bar', array(
					'trophy' => $__vars['trophy'],
					'rule' => $__vars['trophyProgressCriteria']['rule'],
					'dataKey' => $__vars['trophyProgressCriteria']['dataKey'],
					'previousTrophy' => $__vars['previousTrophy'],
					'progressValue' => (($__vars['progressValue'] !== null) ? $__vars['progressValue'] : $__vars['xf']['visitor'][$__vars['trophyProgressCriteria']['valueKey']]),
					'selected' => ($__vars['selectedTrophy'] AND ($__vars['selectedTrophy']['trophy_id'] === $__vars['trophy']['trophy_id'])),
				), $__vars) . '
							';
				$__vars['previousTrophy'] = $__vars['trophy'];
				$__finalCompiled .= '
						';
			}
		}
		$__finalCompiled .= '
					</ul>
				</div>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'trophy_progress_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'trophy' => '!',
		'rule' => '!',
		'dataKey' => '!',
		'previousTrophy' => '',
		'progressValue' => '0',
		'selected' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('thuserimprovements_trophy_progress_bar.less');
	$__finalCompiled .= '
	';
	if ($__templater->isTraversable($__vars['trophy']['user_criteria'])) {
		foreach ($__vars['trophy']['user_criteria'] AS $__vars['userCriteria']) {
			$__finalCompiled .= '
		';
			if (($__vars['userCriteria']['rule'] === $__vars['rule']) AND $__vars['userCriteria']['data'][$__vars['dataKey']]) {
				$__finalCompiled .= '
			';
				$__vars['max'] = $__vars['userCriteria']['data'][$__vars['dataKey']];
				$__finalCompiled .= '
		';
			}
			$__finalCompiled .= '
	';
		}
	}
	$__finalCompiled .= '
	';
	if ($__vars['previousTrophy']) {
		$__finalCompiled .= '
		';
		if ($__templater->isTraversable($__vars['previousTrophy']['user_criteria'])) {
			foreach ($__vars['previousTrophy']['user_criteria'] AS $__vars['userCriteria']) {
				$__finalCompiled .= '
			';
				if (($__vars['userCriteria']['rule'] === $__vars['rule']) AND $__vars['userCriteria']['data'][$__vars['dataKey']]) {
					$__finalCompiled .= '
				';
					$__vars['min'] = $__vars['userCriteria']['data'][$__vars['dataKey']];
					$__finalCompiled .= '
			';
				}
				$__finalCompiled .= '
		';
			}
		}
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '
	';
	if ($__vars['max']) {
		$__finalCompiled .= '
		';
		if (($__vars['progressValue'] > $__vars['min']) AND (($__vars['max'] - $__vars['min']) != 0)) {
			$__finalCompiled .= '
			';
			if ($__vars['progressValue'] < $__vars['max']) {
				$__finalCompiled .= '
				';
				$__vars['progress'] = ((($__vars['progressValue'] - $__vars['min']) / ($__vars['max'] - $__vars['min'])) * 100);
				$__finalCompiled .= '
			';
			} else {
				$__finalCompiled .= '
				';
				$__vars['progress'] = 100;
				$__finalCompiled .= '
			';
			}
			$__finalCompiled .= '
		';
		}
		$__finalCompiled .= '
		<li class="th-trophyProgress">
			<span class="contentRow-figure contentRow-figure--text contentRow-figure--fixedSmall">
				' . $__templater->callMacro('thuserimprovements_trophy_macros', 'trophy', array(
			'trophy' => $__vars['trophy'],
		), $__vars) . '
			</span>
			<div class="th-trophyProgressBar">
				<div class="th-trophyProgressBar-bar' . (($__vars['progress'] == 100) ? ' th-trophyProgressBar-bar--full' : ((!$__vars['progress']) ? ' th-trophyProgressBar-bar--empty' : '')) . '"
					' . (($__vars['progress'] AND ($__vars['progress'] != 100)) ? (('style="width: ' . $__templater->escape($__vars['progress'])) . '%;"') : '') . '></div>
				<span class="th-trophyProgressBar-max">' . $__templater->filter($__vars['max'], array(array('number_short', array(1, )),), true) . '</span>
			</div>
			';
		if ($__vars['selected']) {
			$__finalCompiled .= '
				<span class="th-trophyProgress-trophyTitle th-trophyProgress-trophyTitle--selected">' . $__templater->escape($__vars['trophy']['title']) . '</span>
			';
		} else {
			$__finalCompiled .= '
				<span class="th-trophyProgress-trophyTitle"><a href="' . $__templater->func('link', array('trophies', $__vars['trophy'], ), true) . '">' . $__templater->escape($__vars['trophy']['title']) . '</a></span>
			';
		}
		$__finalCompiled .= '

		</li>
	';
	}
	$__finalCompiled .= '
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