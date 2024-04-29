<?php
// FROM HASH: f6f066d2d79832322614b2ac62f15a1a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRow('
	' . $__templater->formCheckBox(array(
	), array(array(
		'name' => 'sale_item',
		'value' => '1',
		'checked' => ($__vars['post']['Thread']['sale_item'] ? 'checked' : ''),
		'label' => '
				' . 'Sale Item' . '
			',
		'_type' => 'option',
	))) . '
', array(
	));
	return $__finalCompiled;
}
);