<?php
// FROM HASH: c2c105feb9dd5795a99e352ce57049ee
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['trophies']['uncategorized'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			';
		if (($__templater->func('count', array($__vars['trophyCategories'], ), false) > 1)) {
			$__finalCompiled .= '
				<h2 class="block-header">
					' . 'Uncategorized' . '
				</h2>
			';
		}
		$__finalCompiled .= '
			<div class="block-body">
				';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['trophies']['uncategorized'])) {
			foreach ($__vars['trophies']['uncategorized'] AS $__vars['trophy']) {
				$__compilerTemp1 .= '
						' . $__templater->dataRow(array(
					'label' => $__templater->escape($__vars['trophy']['title']),
					'hint' => ($__vars['trophy']['th_hidden'] ? (('[' . 'Hidden trophy') . ']') : '') . ' ' . 'Points' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['trophy']['trophy_points']),
					'href' => $__templater->func('link', array('trophies/edit', $__vars['trophy'], ), false),
					'delete' => $__templater->func('link', array('trophies/delete', $__vars['trophy'], ), false),
				), array()) . '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__compilerTemp1 . '
				', array(
		)) . '
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__templater->isTraversable($__vars['trophyCategories'])) {
		foreach ($__vars['trophyCategories'] AS $__vars['category_id'] => $__vars['category']) {
			$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<h2 class="block-header">
				' . $__templater->func('anchor_target', array('trophy_category_id' . $__vars['category']['trophy_category_id'], ), true) . '
				<a href="' . $__templater->func('link', array('trophies/thui-category/edit', $__vars['category'], ), true) . '" class="u-pullRight">' . 'Edit' . '</a>
				' . $__templater->escape($__vars['category']['title']) . '
			</h2>
			<div class="block-body">
				';
			if (!$__templater->test($__vars['trophies'][$__vars['category_id']], 'empty', array())) {
				$__finalCompiled .= '
					';
				$__compilerTemp2 = '';
				if ($__templater->isTraversable($__vars['trophies'][$__vars['category_id']])) {
					foreach ($__vars['trophies'][$__vars['category_id']] AS $__vars['trophy']) {
						$__compilerTemp2 .= '
							' . $__templater->dataRow(array(
							'label' => $__templater->escape($__vars['trophy']['title']),
							'hint' => 'Points' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['trophy']['trophy_points']),
							'href' => $__templater->func('link', array('trophies/edit', $__vars['trophy'], ), false),
							'delete' => $__templater->func('link', array('trophies/delete', $__vars['trophy'], ), false),
						), array()) . '
						';
					}
				}
				$__finalCompiled .= $__templater->dataList('
						' . $__compilerTemp2 . '
					', array(
				)) . '
				';
			} else {
				$__finalCompiled .= '
					<div class="block-row">
						' . 'No trophies have been created for this category yet. <a href="' . $__templater->func('link', array('trophies/add', null, array('trophy_category_id' => $__vars['category_id'], ), ), true) . '">Add one</a>.' . '
					</div>
				';
			}
			$__finalCompiled .= '
			</div>
		</div>
	</div>
';
		}
	}
	return $__finalCompiled;
}
);