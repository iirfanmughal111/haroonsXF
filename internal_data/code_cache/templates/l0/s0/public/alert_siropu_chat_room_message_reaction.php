<?php
// FROM HASH: 4ad53da3b0aa3a9784e114aeb2d160d0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' reacted to your chat room message ' . (((('<a href="' . $__templater->func('link', array('chat/message/view', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('snippet', array($__vars['content']['message'], 50, array('stripBbCode' => true, ), ), true)) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['extra']['reaction_id'], ), false), array(array('preescaped', array()),), true) . '.';
	return $__finalCompiled;
}
);