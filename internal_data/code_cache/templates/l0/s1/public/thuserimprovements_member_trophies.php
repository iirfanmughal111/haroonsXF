<?php
// FROM HASH: ebdc5e817ff384499a8acd1136ca2310
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block">
	<div class="block-container">
		';
	if (!$__templater->test($__vars['trophies'], 'empty', array())) {
		$__finalCompiled .= '
			<ol class="block-body">
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
					<h2 class="block-header">
						' . $__templater->func('anchor_target', array('trophy_category_id' . $__vars['category']['trophy_category_id'], ), true) . '
						' . $__templater->escape($__vars['category']['title']) . '
					</h2>
					<ol class="block-body">
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
		$__finalCompiled .= '
			';
	} else {
		$__finalCompiled .= '
			<div class="block-body block-row">' . '' . $__templater->escape($__vars['user']['username']) . ' has not been awarded any trophies yet.' . '</div>
		';
	}
	$__finalCompiled .= '
		<div class="block-footer block-footer--split">
			<span class="block-footer-counter">' . 'Total points' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('number', array($__vars['user']['trophy_points'], ), true) . '</span>
			<span class="block-footer-controls">
				' . $__templater->button('
					' . 'View all available trophies' . '
				', array(
		'href' => $__templater->func('link', array('help', array('page_name' => 'trophies', ), ), false),
	), '', array(
	)) . '
			</span>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);