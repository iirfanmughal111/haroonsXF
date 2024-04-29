<?php
// FROM HASH: 58619a4e12d1c5b3dd18cedcc8ce5459
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<h4 class="block-textHeader">' . 'Trophies' . '</h4>
<ol class="listPlain">
	';
	if ($__templater->isTraversable($__vars['trophies']['uncategorized'])) {
		foreach ($__vars['trophies']['uncategorized'] AS $__vars['trophy']) {
			$__finalCompiled .= '
		' . $__templater->callMacro('thuserimprovements_trophy_macros', 'user_trophy_item', array(
				'trophy' => $__vars['trophy'],
			), $__vars) . '
	';
		}
	}
	$__finalCompiled .= '
</ol>

';
	if ($__templater->isTraversable($__vars['trophyCategories'])) {
		foreach ($__vars['trophyCategories'] AS $__vars['category_id'] => $__vars['category']) {
			$__finalCompiled .= '
	';
			if (!$__templater->test($__vars['trophies'][$__vars['category_id']], 'empty', array())) {
				$__finalCompiled .= '
		<h4 class="block-textHeader">' . $__templater->escape($__vars['category']['title']) . '</h4>
		<ol class="listPlain">
			';
				if ($__templater->isTraversable($__vars['trophies'][$__vars['category_id']])) {
					foreach ($__vars['trophies'][$__vars['category_id']] AS $__vars['trophy']) {
						$__finalCompiled .= '
				' . $__templater->callMacro('thuserimprovements_trophy_macros', 'user_trophy_item', array(
							'trophy' => $__vars['trophy'],
						), $__vars) . '
			';
					}
				}
				$__finalCompiled .= '
		</ol>
	';
			}
			$__finalCompiled .= '
';
		}
	}
	return $__finalCompiled;
}
);