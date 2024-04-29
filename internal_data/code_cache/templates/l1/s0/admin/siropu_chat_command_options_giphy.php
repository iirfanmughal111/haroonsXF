<?php
// FROM HASH: c948015a61fab04bba322442db83140c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formTextBoxRow(array(
		'name' => 'command_options[giphy_api_key]',
		'value' => ($__vars['command']['command_options']['giphy_api_key'] ?: ''),
	), array(
		'label' => 'Giphy API Key',
		'explain' => 'In order to use Giphy, you have to obtain an API Key from <a href="https://developers.giphy.com/dashboard/?create=true" target="_blank">https://developers.giphy.com/dashboard/?create=true</a>',
	)) . '

' . $__templater->formRadioRow(array(
		'name' => 'command_options[rating]',
		'value' => ($__vars['command']['command_options']['rating'] ?: ''),
	), array(array(
		'value' => '',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
		'value' => 'g',
		'label' => '<b>G:</b> Content that is appropriate for all ages and people.',
		'_type' => 'option',
	),
	array(
		'value' => 'pg',
		'label' => '<b>PG:</b> Content that is generally safe for everyone, but may require parental preview before children can watch.',
		'_type' => 'option',
	),
	array(
		'value' => 'pg-13',
		'label' => '<b>PG-13:</b> Mild sexual innuendos, mild substance use, mild profanity, or threatening images. May include images of semi-naked people, but DOES NOT show real human genitalia or nudity.',
		'_type' => 'option',
	),
	array(
		'value' => 'r',
		'label' => '<b>R:</b> Strong language, strong sexual innuendo, violence, and illegal drug use; not suitable for teens or younger. NO NUDITY.',
		'_type' => 'option',
	)), array(
		'label' => 'Giphy image rating',
		'explain' => 'GIPHY Content Rating Specifics',
	));
	return $__finalCompiled;
}
);