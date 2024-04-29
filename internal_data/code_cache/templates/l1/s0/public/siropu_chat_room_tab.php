<?php
// FROM HASH: a4f323a0c9f05568aebbbc1749931a4e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<a role="button" data-target="room" data-room-id="' . $__templater->escape($__vars['room']['room_id']) . '" data-leave="' . ($__vars['room']['room_leave'] ? 'true' : 'false') . '" data-title="' . $__templater->filter($__vars['room']['room_name'], array(array('for_attr', array()),), true) . '" data-readonly="' . ($__templater->escape($__vars['room']['room_readonly']) ?: 0) . '" data-flood-check="' . ($__vars['room']['room_flood'] ? ($__vars['room']['room_flood'] * 1000) : 0) . '"' . (($__templater->method($__vars['room'], 'isActive', array()) AND ($__vars['chat']['channel'] == 'room')) ? ' class="siropuChatActiveTab"' : '') . '>' . $__templater->escape($__vars['room']['room_name']);
	if ($__vars['xf']['options']['siropuChatRoomTabUserCount']) {
		$__finalCompiled .= ' <span class="siropuChatTabCount' . ($__templater->method($__vars['room'], 'getUserCount', array($__vars['chat']['users'], )) ? 'Active' : 'Inactive') . '">' . $__templater->escape($__templater->method($__vars['room'], 'getUserCount', array($__vars['chat']['users'], ))) . '</span>';
	}
	$__finalCompiled .= '</a>';
	return $__finalCompiled;
}
);