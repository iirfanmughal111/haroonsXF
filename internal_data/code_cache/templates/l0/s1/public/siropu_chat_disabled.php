<?php
// FROM HASH: 739baa40c74d8e7e31cf1f67fca63d0d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('siropu_chat_disabled.less');
	$__finalCompiled .= '
';
	$__templater->includeJs(array(
		'src' => 'siropu/chat/disabled.js',
		'min' => 'true',
	));
	$__finalCompiled .= '

' . $__templater->button('Enable Chat', array(
		'id' => 'siropuChat',
		'class' => $__vars['cssClass'] . ' siropuChatEnable button--link',
		'data-xf-click' => 'siropu-chat-enable',
		'fa' => 'fa-comments',
	), '', array(
	));
	return $__finalCompiled;
}
);