<?php
// FROM HASH: 278595be9cffb013687d327729ccdd84
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'XF.SiropuChat.Core = XF.extend(XF.SiropuChat.Core, {
	userId: ' . $__templater->escape($__vars['xf']['visitor']['user_id']) . ',
	canUseChat: ' . ($__templater->method($__vars['xf']['visitor'], 'canUseSiropuChat', array()) ? 'true' : 'false') . ',
	serverTime: ' . $__templater->escape($__vars['xf']['time']) . ',
	channel: \'' . $__templater->escape($__vars['chat']['channel']) . '\',
	roomId: ' . $__templater->escape($__vars['chat']['roomId']) . ',
	lastId: ' . $__templater->filter($__vars['chat']['lastRoomIds'], array(array('json', array()),array('raw', array()),), true) . ',
	convId: ' . $__templater->escape($__vars['chat']['convId']) . ',
	convUnread: ' . $__templater->filter($__vars['chat']['convUnread'], array(array('json', array()),array('raw', array()),), true) . ',
	convOnly: ' . ($__vars['chat']['convOnly'] ? 1 : 0) . ',
	convItems: ' . $__templater->filter($__vars['chat']['convIds'], array(array('json', array()),array('raw', array()),), true) . ',
	guestRoom: ' . $__templater->escape($__vars['xf']['options']['siropuChatGuestRoom']) . ',
	messageDisplayLimit: ' . ($__templater->escape($__vars['chat']['messageDisplayLimit']) ?: 25) . ',
	notificationTimeout: ' . ($__vars['xf']['options']['siropuChatNotificationTimeout'] * 1000) . ',
	refreshActive: ' . ($__vars['xf']['options']['siropuChatRefreshIntervalActive'] * 1000) . ',
	refreshActiveHidden: ' . ($__vars['xf']['options']['siropuChatRefreshIntervalActiveHidden'] * 1000) . ',
	refreshInactive: ' . ($__vars['xf']['options']['siropuChatRefreshIntervalInactive'] * 1000) . ',
	refreshInactiveHidden: ' . ($__vars['xf']['options']['siropuChatRefreshIntervalInactiveHidden'] * 1000) . ',
	tabNotification: ' . ($__vars['xf']['options']['siropuChatTabNotification'] ? 'true' : 'false') . ',
	inverse: ' . ($__templater->escape($__vars['chat']['settings']['inverse']) ?: 0) . ',
	floodCheck: ' . ($__vars['xf']['options']['siropuChatFloodCheckLength'] * 1000) . ',
	roomFloodCheck: ' . ($__vars['chat']['rooms'] ? ($__vars['chat']['rooms'][$__vars['chat']['roomId']]['room_flood'] * 1000) : 0) . ',
	bypassFloodCheck: ' . ($__templater->method($__vars['xf']['visitor'], 'canBypassSiropuChatFloodCheck', array()) ? 'true' : 'false') . ',
	displayNavCount: ' . (($__vars['xf']['options']['siropuChatPage'] AND $__vars['xf']['options']['siropuChatNavUserCount']) ? 'true' : 'false') . ',
	dynamicTitle: ' . ($__vars['xf']['options']['siropuChatDynamicTitle'] ? 'true' : 'false') . ',
	activeUsers: ' . $__templater->filter($__vars['chat']['userIds'], array(array('raw', array()),array('json', array()),), true) . ',
	commands: ' . $__templater->filter($__vars['chat']['commands'], array(array('raw', array()),array('json', array()),), true) . ',
	loadMoreButton: {room: ' . (($__vars['xf']['options']['siropuChatRoomLoadMoreButton'] AND $__templater->method($__vars['xf']['visitor'], 'canViewSiropuChatArchive', array())) ? 'true' : 'false') . ', conv: ' . ($__vars['xf']['options']['siropuChatConvLoadMoreButton'] ? 'true' : 'false') . '},
	loadRooms: ' . ($__vars['xf']['options']['siropuChatLoadRoomsIfNoneJoined'] ? 'true' : 'false') . ',
	internalLinksInNewTab: ' . ($__vars['xf']['options']['siropuChatOpenInternalLinksInNewTab'] ? 'true' : 'false') . ',
	roomTabUserCount: ' . ($__vars['xf']['options']['siropuChatRoomTabUserCount'] ? 'true' : 'false') . ',
	convTabCount: \'' . $__templater->escape($__vars['xf']['options']['siropuChatConvTabCount']) . '\',
	userListOrder: \'' . $__templater->escape($__vars['xf']['options']['siropuChatUsersOrder']) . '\',
	hideTabs: ' . ($__vars['chat']['hideTabs'] ? 'true' : 'false') . ',
	whosTyping: ' . $__templater->filter($__vars['xf']['options']['siropuChatEnableWhosTyping'], array(array('json', array()),array('raw', array()),), true) . ',
	forceAutoScroll: false
});

jQuery.extend(XF.phrases, {
	siropu_chat_write_public_message: "' . $__templater->filter('Write a public message...', array(array('escape', array('js', )),), true) . '",
	siropu_chat_write_private_message: "' . $__templater->filter('Write a private message...', array(array('escape', array('js', )),), true) . '",
	siropu_chat_new_message: "' . $__templater->filter('Chat: New message', array(array('escape', array('js', )),), true) . '",
	siropu_chat_new_public_message: "' . $__templater->filter('Chat: New public message', array(array('escape', array('js', )),), true) . '",
	siropu_chat_new_whisper_message: "' . $__templater->filter('Chat: New whisper message', array(array('escape', array('js', )),), true) . '",
	siropu_chat_new_private_message: "' . $__templater->filter('Chat: New private message', array(array('escape', array('js', )),), true) . '",
	siropu_chat_posting_message: "' . $__templater->filter('Posting message...', array(array('escape', array('js', )),), true) . '",
	siropu_chat_please_wait: "' . $__templater->filter('Please wait...', array(array('escape', array('js', )),), true) . '",
	siropu_chat_please_wait_x_seconds: "' . $__templater->filter('Please wait ' . $__vars['xf']['options']['siropuChatFloodCheckLength'] . ' seconds...', array(array('escape', array('js', )),), true) . '",
	siropu_chat_no_response: "' . $__templater->filter('The server has not responded in time. :(', array(array('escape', array('js', )),), true) . '",
	siropu_chat_loading_conversation: "' . $__templater->filter('Loading conversation...', array(array('escape', array('js', )),), true) . '",
	siropu_chat_confirm_conversation_leave: "' . $__templater->filter('Are you sure you want to leave this conversation?', array(array('escape', array('js', )),), true) . '",
	siropu_chat_settings_change_reload_page: "' . $__templater->filter('Please reload the page to see the changes.', array(array('escape', array('js', )),), true) . '",
	siropu_chat_room_is_read_only: "' . $__templater->filter('This room is read only.', array(array('escape', array('js', )),), true) . '",
	siropu_chat_no_conversations_started: "' . $__templater->filter('You haven\'t started any conversations yet.', array(array('escape', array('js', )),), true) . '",
	siropu_chat_load_more_messages: "' . $__templater->filter('Load more messages?', array(array('escape', array('js', )),), true) . '",
	siropu_chat_all_messages_have_been_loaded: "' . $__templater->filter('All messages have been loaded.', array(array('escape', array('js', )),), true) . '",
	siropu_chat_loading_conversation_messages: "' . $__templater->filter('Loading conversation messages...', array(array('escape', array('js', )),), true) . '",
	siropu_chat_has_been_disabled: "' . $__templater->filter('Chat has been disabled. You can enable it again from the same location.', array(array('escape', array('js', )),), true) . '",
	siropu_chat_do_not_have_permission_to_use: "' . $__templater->filter('You do not have the permission to use the chat.', array(array('escape', array('js', )),), true) . '",
	siropu_chat_no_one_is_chatting: "' . $__templater->filter('No one is chatting at the moment.', array(array('escape', array('js', )),), true) . '",
	siropu_chat_is_typing: "' . $__templater->filter('is typing...', array(array('escape', array('js', )),), true) . '",
});

' . $__templater->includeTemplate('siropu_chat_custom_js', $__vars);
	return $__finalCompiled;
}
);