<?php
// FROM HASH: 0b83aacdad636a2feda5d8396ae76387
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRadioRow(array(
		'name' => $__vars['inputName'] . '[gptmodel]',
		'value' => $__vars['option']['option_value']['gptmodel'],
	), array(array(
		'value' => 'gptChat',
		'label' => 'Chat',
		'data-hide' => 'true',
		'hint' => '/v1/chat/completion endpoints',
		'_dependent' => array('
			' . $__templater->formTextBox(array(
		'name' => $__vars['inputName'] . '[gptChat_model]',
		'placeholder' => 'Model name......',
		'value' => $__vars['option']['option_value']['gptChat_model'],
		'required' => 'true',
	)) . '
		'),
		'_type' => 'option',
	),
	array(
		'value' => 'gptDev',
		'label' => 'GPT-DAV',
		'data-hide' => 'true',
		'hint' => '/v1/completion endpoints',
		'_dependent' => array('
			' . $__templater->formTextBox(array(
		'name' => $__vars['inputName'] . '[gptDev_model]',
		'placeholder' => 'Model name......',
		'value' => $__vars['option']['option_value']['gptDev_model'],
		'required' => 'true',
	)) . '
		'),
		'_type' => 'option',
	)), array(
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);