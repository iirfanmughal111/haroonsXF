<?php
// FROM HASH: e5534147063910a65ff8b09010909ff2
return array(
'macros' => array('trophies' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'active' => '!',
		'trophies' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (!$__templater->test($__vars['trophies'], 'empty', array())) {
		$__finalCompiled .= '
		';
		if ($__vars['active']['sort_order'] === 'message_count') {
			$__finalCompiled .= '
			' . $__templater->callMacro(null, 'trophy_progress', array(
				'trophies' => $__vars['trophies'],
				'rule' => 'messages_posted',
				'dataKey' => 'messages',
				'progressValue' => $__vars['xf']['visitor']['message_count'],
			), $__vars) . '
			';
		} else if ($__vars['active']['sort_order'] === 'reaction_score') {
			$__finalCompiled .= '
			' . $__templater->callMacro(null, 'trophy_progress', array(
				'trophies' => $__vars['trophies'],
				'rule' => 'reaction_score',
				'dataKey' => 'reactions',
				'progressValue' => $__vars['xf']['visitor']['reaction_score'],
			), $__vars) . '
		';
		}
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'trophy_progress' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'trophies' => '!',
		'rule' => '!',
		'dataKey' => '!',
		'progressValue' => '0',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
							';
	if ($__templater->isTraversable($__vars['trophies'])) {
		foreach ($__vars['trophies'] AS $__vars['trophy']) {
			$__compilerTemp1 .= '
								' . $__templater->callMacro('thuserimprovements_trophy_progress_macros', 'trophy_progress_bar', array(
				'trophy' => $__vars['trophy'],
				'rule' => $__vars['rule'],
				'dataKey' => $__vars['dataKey'],
				'previousTrophy' => $__vars['previousTrophy'],
				'progressValue' => $__vars['progressValue'],
			), $__vars) . '
								';
			$__vars['previousTrophy'] = $__vars['trophy'];
			$__compilerTemp1 .= '
							';
		}
	}
	$__compilerTemp1 .= '
						';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="block">
			<div class="block-container">
				<div class="block-body block-row th-trophyProgressContainer">
					<ul class="th-trophyProgressBarContainer">
						' . $__compilerTemp1 . '
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
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);