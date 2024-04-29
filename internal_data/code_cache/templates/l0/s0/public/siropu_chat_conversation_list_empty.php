<?php
// FROM HASH: fe68c8bde574d400bf16983a09a5be31
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li id="siropuChatNoConversations">
	' . 'No private conversations found.' . '
	' . $__templater->callMacro('siropu_chat_conversation_start', 'form', array(), $__vars) . '
</li>';
	return $__finalCompiled;
}
);