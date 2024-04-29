<?php
// FROM HASH: 44c10c1f4c35838144df2020dd7ffc8e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRow('
	' . $__templater->formCodeEditor(array(
		'mode' => 'json',
		'name' => $__vars['inputName'],
		'value' => $__vars['option']['option_value'],
		'class' => 'codeEditor--autoSize',
	)) . '
', array(
		'rowtype' => 'input',
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
		'rowclass' => $__vars['rowClass'],
	));
	return $__finalCompiled;
}
);