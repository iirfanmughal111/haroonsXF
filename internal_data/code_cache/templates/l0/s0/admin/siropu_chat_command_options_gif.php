<?php
// FROM HASH: e64df6fe59fc4bc4d85ee37bfbb7621f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->includeTemplate('siropu_chat_command_options_giphy', $__vars) . '

<hr class="formRowSep" />

' . $__templater->formTextBoxRow(array(
		'name' => 'command_options[tenor_api_key]',
		'value' => ($__vars['command']['command_options']['tenor_api_key'] ?: ''),
	), array(
		'label' => 'Tenor API Key',
		'explain' => 'In order to use Tenor, you have to obtain an API Key from <a href="https://tenor.com/developer/keyregistration" target="_blank">https://tenor.com/developer/keyregistration</a>',
	));
	return $__finalCompiled;
}
);