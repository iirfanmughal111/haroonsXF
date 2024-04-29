<?php
// FROM HASH: 338fa200a31fbb56d12a305b34be4c10
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'th_hidden',
		'value' => '1',
		'selected' => $__vars['trophy']['th_hidden'],
		'label' => '
		' . 'Hidden trophy' . '
	',
		'_type' => 'option',
	)), array(
	)) . '

';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['trophies'])) {
		foreach ($__vars['trophies'] AS $__vars['predecessor']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['predecessor']['trophy_id'],
				'label' => $__templater->escape($__vars['predecessor']['title']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'th_predecessor',
		'value' => $__vars['trophy']['th_predecessor'],
	), $__compilerTemp1, array(
		'label' => 'Predecessor',
	)) . '

';
	$__compilerTemp2 = array(array(
		'value' => '',
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['categories'])) {
		foreach ($__vars['categories'] AS $__vars['category']) {
			$__compilerTemp2[] = array(
				'value' => $__vars['category']['trophy_category_id'],
				'label' => $__templater->escape($__vars['category']['title']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'th_trophy_category_id',
		'value' => $__vars['trophy']['th_trophy_category_id'],
	), $__compilerTemp2, array(
		'label' => 'Trophy category',
	));
	return $__finalCompiled;
}
);