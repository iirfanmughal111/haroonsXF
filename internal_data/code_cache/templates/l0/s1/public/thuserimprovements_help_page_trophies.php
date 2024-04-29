<?php
// FROM HASH: a253cb673484dc8cebba7b3ef161e135
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('thuserimprovements_help_page_trophies.less');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['trophies']['uncategorized'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<ol class="block-body">
				';
		if ($__templater->isTraversable($__vars['trophies']['uncategorized'])) {
			foreach ($__vars['trophies']['uncategorized'] AS $__vars['trophy']) {
				$__finalCompiled .= '
					' . $__templater->callMacro('thuserimprovements_trophy_macros', 'trophy_item', array(
					'trophy' => $__vars['trophy'],
				), $__vars) . '
				';
			}
		}
		$__finalCompiled .= '
			</ol>
			<div class="block-footer">
				';
		$__vars['trophiesEarned']['uncategorized'] = '0';
		$__finalCompiled .= '
				';
		if ($__templater->isTraversable($__vars['trophies']['uncategorized'])) {
			foreach ($__vars['trophies']['uncategorized'] AS $__vars['trophy']) {
				$__finalCompiled .= '
					';
				if ($__vars['trophy']['earned']) {
					$__finalCompiled .= '
						';
					$__vars['trophiesEarned']['uncategorized'] = ($__vars['trophiesEarned']['uncategorized'] + 1);
					$__finalCompiled .= '
					';
				}
				$__finalCompiled .= '
				';
			}
		}
		$__finalCompiled .= '
				' . 'You\'ve earned ' . $__templater->escape($__vars['trophiesEarned']['uncategorized']) . ' out of ' . $__templater->func('count', array($__vars['trophies']['uncategorized'], ), true) . ' trophies in this category.' . '
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__templater->isTraversable($__vars['trophyCategories'])) {
		foreach ($__vars['trophyCategories'] AS $__vars['categoryId'] => $__vars['category']) {
			$__finalCompiled .= '
	';
			if (!$__templater->test($__vars['trophies'][$__vars['categoryId']], 'empty', array())) {
				$__finalCompiled .= '
		<div class="block">
			<div class="block-container">
				<h2 class="block-header">
					' . $__templater->func('anchor_target', array('trophy_category_id' . $__vars['category']['trophy_category_id'], ), true) . '
					<a href="' . $__templater->func('link', array('trophy-categories', $__vars['category'], ), true) . '">' . $__templater->escape($__vars['category']['title']) . '</a>
				</h2>
				<ol class="block-body">
					';
				if ($__templater->isTraversable($__vars['trophies'][$__vars['categoryId']])) {
					foreach ($__vars['trophies'][$__vars['categoryId']] AS $__vars['trophy']) {
						$__finalCompiled .= '
						' . $__templater->callMacro('thuserimprovements_trophy_macros', 'trophy_item', array(
							'trophy' => $__vars['trophy'],
						), $__vars) . '
					';
					}
				}
				$__finalCompiled .= '
				</ol>
				<div class="block-footer">
					';
				$__vars['trophiesEarned'][$__vars['categoryId']] = '0';
				$__finalCompiled .= '
					';
				if ($__templater->isTraversable($__vars['trophies'][$__vars['categoryId']])) {
					foreach ($__vars['trophies'][$__vars['categoryId']] AS $__vars['trophy']) {
						$__finalCompiled .= '
						';
						if ($__vars['trophy']['earned']) {
							$__finalCompiled .= '
							';
							$__vars['trophiesEarned'][$__vars['categoryId']] = ($__vars['trophiesEarned'][$__vars['categoryId']] + 1);
							$__finalCompiled .= '
						';
						}
						$__finalCompiled .= '
					';
					}
				}
				$__finalCompiled .= '
					' . 'You\'ve earned ' . $__templater->escape($__vars['trophiesEarned'][$__vars['categoryId']]) . ' out of ' . $__templater->func('count', array($__vars['trophies'][$__vars['categoryId']], ), true) . ' trophies in this category.' . '
				</div>
			</div>
		</div>
	';
			}
			$__finalCompiled .= '
';
		}
	}
	return $__finalCompiled;
}
);