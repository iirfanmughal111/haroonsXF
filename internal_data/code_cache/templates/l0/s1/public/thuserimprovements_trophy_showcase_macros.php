<?php
// FROM HASH: 61af999c17a06b985b08826ab178186b
return array(
'macros' => array('showcase_display' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'user' => '!',
		'position' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['xf']['options']['klUIProfileTrophyShowcase'] AND $__vars['user']) {
		$__finalCompiled .= '
		';
		$__compilerTemp1 = '';
		$__compilerTemp1 .= '
					';
		if ($__vars['xf']['options']['klUIProfileTrophyShowcase'] == 1) {
			$__compilerTemp1 .= '
						' . $__templater->callMacro(null, 'showcase_items', array(
				'trophies' => $__templater->method($__vars['user'], 'getTHUILatestTrophies', array($__vars['position'], )),
			), $__vars) . '
						';
		} else if ($__vars['xf']['options']['klUIProfileTrophyShowcase'] == 2) {
			$__compilerTemp1 .= '
						' . $__templater->callMacro(null, 'showcase_items', array(
				'trophies' => $__templater->method($__vars['user'], 'getTHUIHighestTrophies', array($__vars['position'], )),
			), $__vars) . '
						';
		} else if ($__vars['xf']['options']['klUIProfileTrophyShowcase'] == 3) {
			$__compilerTemp1 .= '
						' . $__templater->callMacro(null, 'showcase_items', array(
				'trophies' => $__templater->method($__vars['user'], 'getTHUIUserChoiceTrophies', array($__vars['position'], )),
			), $__vars) . '
					';
		}
		$__compilerTemp1 .= '
				';
		if (strlen(trim($__compilerTemp1)) > 0) {
			$__finalCompiled .= '
			<div class="trophyShowcase trophyShowcase--' . $__templater->escape($__vars['position']) . '">
				';
			if ((($__vars['xf']['options']['klUIProfileTrophyShowcase'] == 3) AND ($__vars['user']['user_id'] === $__vars['xf']['visitor']['user_id']))) {
				$__finalCompiled .= '
					<a class="trophyIconItem"
					   data-xf-init="tooltip"
					   title="' . 'th_userimprovements_edit_showcase' . '"
					   href="' . $__templater->func('link', array('members/thui-trophies/showcase-select', $__vars['xf']['visitor'], ), true) . '"
					   data-xf-click="overlay">
						' . $__templater->fontAwesome('fa-edit', array(
				)) . '
					</a>
				';
			}
			$__finalCompiled .= '
				' . $__compilerTemp1 . '
			</div>
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
'showcase_items' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'trophies' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('thuserimprovements_trophy_showcase_macros.less');
	$__finalCompiled .= '

	';
	if ($__templater->isTraversable($__vars['trophies'])) {
		foreach ($__vars['trophies'] AS $__vars['trophy']) {
			$__finalCompiled .= '
		<a href="' . $__templater->func('link', array('trophies', $__vars['trophy']['Trophy'], ), true) . '"
		   class="trophyIconItem"
		   title="' . $__templater->escape($__vars['trophy']['Trophy']['title']) . '"
		   data-xf-init="tooltip">
			';
			if ($__vars['trophy']['Trophy']['th_icon_type'] === 'fa') {
				$__finalCompiled .= '
				<i class="fa far fa-' . $__templater->escape($__vars['trophy']['Trophy']['th_icon_value']) . ' ' . $__templater->escape($__vars['trophy']['Trophy']['th_icon_value']) . '" 
				   style="' . $__templater->escape($__vars['trophy']['Trophy']['th_icon_css']) . '"></i>
				';
			} else if ($__vars['trophy']['Trophy']['th_icon_type'] === 'image') {
				$__finalCompiled .= '
				<img src="' . $__templater->escape($__vars['trophy']['Trophy']['th_icon_value']) . '" style="' . $__templater->escape($__vars['trophy']['Trophy']['th_icon_css']) . '"/>
				';
			} else {
				$__finalCompiled .= '
				<span style="' . $__templater->escape($__vars['trophy']['Trophy']['th_icon_css']) . '">' . $__templater->escape($__vars['trophy']['Trophy']['trophy_points']) . '</span>
			';
			}
			$__finalCompiled .= '
		</a>
	';
		}
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