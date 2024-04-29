<?php
// FROM HASH: cf50c580c55d3ec5d851f34af2bda158
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reacted to your chat conversation message ' . $__templater->func('snippet', array($__vars['content']['message'], 50, array('stripBbCode' => true, ), ), true) . ' with ' . $__templater->func('reaction_title', array($__vars['extra']['reaction_id'], ), true) . '.' . '

<push:url>' . $__templater->func('link', array('canonical:chat/conversation/message', $__vars['content'], ), true) . '</push:url>
<push:tag>siropu_chat_conv_message_reaction_' . $__templater->escape($__vars['content']['message_id']) . '_' . $__templater->escape($__vars['extra']['reaction_id']) . '</push:tag>';
	return $__finalCompiled;
}
);