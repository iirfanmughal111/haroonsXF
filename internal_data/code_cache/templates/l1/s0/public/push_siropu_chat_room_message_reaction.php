<?php
// FROM HASH: 538c30c3991b5fb6a797673348d78946
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reacted to your chat room message ' . $__templater->func('snippet', array($__vars['content']['message'], 50, array('stripBbCode' => true, ), ), true) . ' with ' . $__templater->func('reaction_title', array($__vars['extra']['reaction_id'], ), true) . '.' . '

<push:url>' . $__templater->func('link', array('canonical:chat/message/view', $__vars['content'], ), true) . '</push:url>
<push:tag>siropu_chat_room_message_reaction_' . $__templater->escape($__vars['content']['message_id']) . '_' . $__templater->escape($__vars['extra']['reaction_id']) . '</push:tag>';
	return $__finalCompiled;
}
);