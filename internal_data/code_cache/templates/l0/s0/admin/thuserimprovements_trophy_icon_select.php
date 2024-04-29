<?php
// FROM HASH: b44546bfd3c3df36db0f5b7551f66c09
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formRadioRow(array(
		'name' => 'th_icon_type',
		'value' => $__vars['trophy']['th_icon_type'],
	), array(array(
		'value' => '',
		'label' => 'None',
		'_type' => 'option',
	),
	array(
		'value' => 'fa',
		'label' => 'admin_navigation_icon',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'th_icon_fa',
		'value' => (($__vars['trophy']['th_icon_type'] == 'fa') ? $__vars['trophy']['th_icon_value'] : ''),
		'maxlength' => $__templater->func('max_length', array($__vars['trophy'], 'th_icon_value', ), false),
		'dir' => 'ltr',
	))),
		'_type' => 'option',
	),
	array(
		'value' => 'image',
		'label' => 'Image',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'th_icon_image',
		'value' => (($__vars['trophy']['th_icon_type'] == 'image') ? $__vars['trophy']['th_icon_value'] : ''),
		'maxlength' => $__templater->func('max_length', array($__vars['trophy'], 'th_icon_value', ), false),
		'dir' => 'ltr',
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Trophy icon',
	)) . '

' . $__templater->formCodeEditorRow(array(
		'name' => 'th_icon_css',
		'value' => $__vars['trophy']['th_icon_css'],
		'mode' => 'css',
		'data-line-wrapping' => 'true',
		'class' => 'codeEditor--autoSize',
	), array(
		'label' => 'Icon CSS',
	)) . '

<hr class="formRowSep" />';
	return $__finalCompiled;
}
);