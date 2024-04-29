<?php
// FROM HASH: 885d1fec0334ca662b9717f86bbd5415
return array(
'macros' => array('form' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'recipient' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	if (!$__vars['recipient']) {
		$__compilerTemp1 .= '
			<p><input type="text" name="recipient" class="input" placeholder="' . 'Recipient' . '" data-xf-init="auto-complete" data-single="true"></p>
		';
	}
	$__finalCompiled .= $__templater->form('
		' . $__compilerTemp1 . '
		<p><textarea name="message" class="input" placeholder="' . 'Message' . '" rows="5"></textarea></p>
		<p>' . $__templater->button('Start private conversation', array(
		'type' => 'submit',
		'fa' => 'fas fa-user-plus',
	), '', array(
	)) . '</p>
	', array(
		'action' => $__templater->func('link', array('chat/conversation/start', $__vars['recipient'], ), false),
		'data-xf-init' => 'siropu-chat-start-conversation-form',
	)) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);