<?php
// FROM HASH: a47951439b2b2309d7ade1a2274f8ca5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['trophies'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if (!$__templater->test($__vars['trophies']['uncategorized'], 'empty', array())) {
			$__compilerTemp1 .= '
					';
			$__compilerTemp2 = array();
			if ($__templater->isTraversable($__vars['trophies']['uncategorized'])) {
				foreach ($__vars['trophies']['uncategorized'] AS $__vars['trophy']) {
					$__compilerTemp2[] = array(
						'value' => $__vars['trophy']['trophy_id'],
						'label' => $__templater->escape($__vars['trophy']['title']),
						'_type' => 'option',
					);
				}
			}
			$__compilerTemp1 .= $__templater->formCheckBoxRow(array(
				'name' => 'trophies',
			), $__compilerTemp2, array(
				'label' => 'Uncategorized trophies',
			)) . '
				';
		}
		$__compilerTemp3 = '';
		if ($__templater->isTraversable($__vars['trophyCategories'])) {
			foreach ($__vars['trophyCategories'] AS $__vars['category']) {
				$__compilerTemp3 .= '
					';
				if (!$__templater->test($__vars['trophies'][$__vars['category']['trophy_category_id']], 'empty', array())) {
					$__compilerTemp3 .= '
						';
					$__compilerTemp4 = array();
					if ($__templater->isTraversable($__vars['trophies'][$__vars['category']['trophy_category_id']])) {
						foreach ($__vars['trophies'][$__vars['category']['trophy_category_id']] AS $__vars['trophy']) {
							$__compilerTemp4[] = array(
								'value' => $__vars['trophy']['trophy_id'],
								'label' => $__templater->escape($__vars['trophy']['title']),
								'_type' => 'option',
							);
						}
					}
					$__compilerTemp3 .= $__templater->formCheckBoxRow(array(
						'name' => 'trophies',
					), $__compilerTemp4, array(
						'label' => $__templater->escape($__vars['category']['title']),
					)) . '
					';
				}
				$__compilerTemp3 .= '
				';
			}
		}
		$__finalCompiled .= $__templater->form('
		<div class="block-container">
			<h2 class="block-header">
				' . 'Reward trophies' . '
			</h2>
			<div class="block-body">
				' . $__templater->formTextBoxRow(array(
			'name' => 'users',
			'ac' => 'true',
		), array(
			'label' => 'Users',
		)) . '

				' . $__compilerTemp1 . '

				' . $__compilerTemp3 . '
			</div>
			' . $__templater->formSubmitRow(array(
			'sticky' => 'true',
			'icon' => 'save',
		), array(
		)) . '
		</div>
	', array(
			'class' => 'block',
			'action' => $__templater->func('link', array('trophies/thui-reward-save', ), false),
			'ajax' => 'true',
		)) . '
';
	}
	return $__finalCompiled;
}
);