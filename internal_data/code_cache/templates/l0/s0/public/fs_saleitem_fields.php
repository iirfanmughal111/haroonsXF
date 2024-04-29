<?php
// FROM HASH: 0566f54e16a96492c65c2f94fba5ba4d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRow('
	' . $__templater->formCheckBox(array(
	), array(array(
		'name' => 'sale_item',
		'value' => '1',
		'checked' => ($__vars['Thread']['sale_item'] ? 'checked' : ''),
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