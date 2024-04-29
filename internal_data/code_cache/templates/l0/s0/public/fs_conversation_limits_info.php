<?php
// FROM HASH: 363dccffdbe32517ba8b13b82fbdfd36
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<style>
	.limit-info{margin-right: 10px;}
</style>

';
	$__vars['conversasionLimit'] = $__templater->method($__vars['xf']['visitor'], 'hasPermission', array('fs_limitations', 'fs_conversationLimit', ));
	$__finalCompiled .= '
';
	$__vars['visitorConversationMessageCount'] = $__vars['xf']['visitor']['conversation_message_count'];
	$__finalCompiled .= '

';
	$__vars['upgradeUrl'] = (($__vars['xf']['visitor']['account_type'] == 1) ? $__templater->func('link', array('account-upgrade/admirer', ), false) : $__templater->func('link', array('account-upgrade/companion', ), false));
	$__finalCompiled .= '

';
	if ($__vars['conversasionLimit'] == 0) {
		$__finalCompiled .= '
	<span class="limit-info">' . 'Conversation not allowed please <a href="' . $__templater->escape($__vars['upgradeUrl']) . '"> upgrade </a> your account.' . '</span>
';
	} else if ($__vars['conversasionLimit'] > 0) {
		$__finalCompiled .= '
	';
		$__vars['remianingCount'] = ($__vars['conversasionLimit'] - $__vars['visitorConversationMessageCount']);
		$__finalCompiled .= '
	<span class="limit-info">' . 'Your Conversation limit: <b>( ' . $__templater->escape($__vars['visitorConversationMessageCount']) . ' / ' . $__templater->escape($__vars['conversasionLimit']) . ' )</b> .&nbsp;&nbsp;
Remaining:  <b>' . $__templater->escape($__vars['remianingCount']) . '</b>' . '</span>
';
	} else {
		$__finalCompiled .= '
	<span class="limit-info">' . 'Your conversation limit: <b>Unlimited</b>' . '</span>
';
	}
	return $__finalCompiled;
}
);