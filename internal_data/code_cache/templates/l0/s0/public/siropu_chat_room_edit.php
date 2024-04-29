<?php
// FROM HASH: ad03de25b8a74d35437fc5076b68826d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['room'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Create room');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit room' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['room']['room_name']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'siropu/chat/room.js',
		'min' => 'true',
	));
	$__finalCompiled .= '

';
	$__templater->inlineCss('
	.siropuChatRoomEdit .iconic
	{
		width: auto;
	}
');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['xf']['visitor'], 'canSetSiropuChatRoomUsers', array()) AND (!$__templater->method($__vars['room'], 'isMain', array()))) {
		$__compilerTemp1 .= '
				' . $__templater->formTokenInputRow(array(
			'name' => 'room_users',
			'value' => $__templater->filter($__templater->func('array_values', array($__vars['room']['room_users'], ), false), array(array('join', array(',', )),), false),
			'href' => $__templater->func('link', array('members/find', ), false),
		), array(
			'label' => 'Room users',
			'explain' => 'The names of the users who can join the room. Users will automatically join the room and get a notification about it.',
			'hint' => 'Optional',
		)) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['xf']['visitor'], 'canPasswordProtectSiropuChatRooms', array()) AND (!$__templater->method($__vars['room'], 'isMain', array()))) {
		$__compilerTemp2 .= '
				' . $__templater->formTextBoxRow(array(
			'name' => 'room_password',
			'value' => $__vars['room']['room_password'],
			'maxlength' => $__templater->func('max_length', array($__vars['room'], 'room_password', ), false),
		), array(
			'label' => 'Room password',
			'explain' => 'Allow room access using a password.',
			'hint' => 'Optional',
		)) . '
			';
	}
	$__compilerTemp3 = '';
	if ($__templater->method($__vars['xf']['visitor'], 'canEditSiropuChatRoomSettings', array())) {
		$__compilerTemp3 .= '
				<h2 class="block-formSectionHeader">
					<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up :next">
						<span class="block-formSectionHeader-aligner">' . 'Admin options' . '</span>
					</span>
				</h2>

				<div class="block-body block-body--collapsible">
					';
		$__compilerTemp4 = array();
		if ($__templater->isTraversable($__vars['userGroups'])) {
			foreach ($__vars['userGroups'] AS $__vars['group']) {
				$__compilerTemp4[] = array(
					'name' => 'room_user_groups[]',
					'value' => $__vars['group']['user_group_id'],
					'label' => $__templater->escape($__vars['group']['title']),
					'checked' => $__templater->func('in_array', array($__vars['group']['user_group_id'], $__vars['room']['room_user_groups'], ), false),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp3 .= $__templater->formCheckBoxRow(array(
		), $__compilerTemp4, array(
			'label' => 'User groups',
			'explain' => 'This option allows you to enable room access based on user group.',
		)) . '

					';
		$__compilerTemp5 = array(array(
			'_type' => 'option',
		));
		if ($__templater->isTraversable($__vars['languages'])) {
			foreach ($__vars['languages'] AS $__vars['language']) {
				$__compilerTemp5[] = array(
					'value' => $__vars['language']['record']['language_id'],
					'label' => $__templater->func('repeat', array('--', $__vars['language']['depth'], ), true) . ' ' . $__templater->escape($__vars['language']['record']['title']),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp3 .= $__templater->formSelectRow(array(
			'name' => 'room_language_id',
			'value' => $__vars['room']['room_language_id'],
		), $__compilerTemp5, array(
			'label' => 'User language',
			'explain' => 'This option allows you to display room based on the user\'s account language setting.',
		)) . '

					<hr class="formRowSep" />

					' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'room_leave',
			'value' => '1',
			'label' => 'Can leave room',
			'checked' => ($__vars['room']['room_leave'] == true),
			'_type' => 'option',
		)), array(
			'explain' => 'This option allows you to control if the user can leave or not the room once joined.',
		)) . '

					<hr class="formRowSep" />

					' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'room_readonly',
			'value' => '1',
			'label' => 'Read only',
			'checked' => ($__vars['room']['room_readonly'] == true),
			'_type' => 'option',
		)), array(
			'explain' => 'If checked, users who join this room won\'t be able to post anything.',
		)) . '

					<hr class="formRowSep" />

					' . $__templater->formCheckBoxRow(array(
		), array(array(
			'label' => 'Locked',
			'checked' => ($__vars['room']['room_locked'] != 0),
			'_dependent' => array('
								' . $__templater->formDateInput(array(
			'name' => 'room_locked',
			'value' => ($__vars['room']['room_locked'] ? $__templater->func('date', array($__vars['room']['room_locked'], 'Y-m-d', ), false) : ''),
			'placeholder' => 'Until' . $__vars['xf']['language']['ellipsis'],
		)) . '
							'),
			'_type' => 'option',
		)), array(
			'explain' => 'If locked, users won\'t be able to join the room until the set date.',
		)) . '

					<hr class="formRowSep" />

					' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'room_rss',
			'value' => '1',
			'label' => 'RSS',
			'checked' => ($__vars['room']['room_rss'] == true),
			'_type' => 'option',
		)), array(
			'explain' => 'This option allows you to get room messages in a RSS feed. Once enabled, the RSS link will show up when you click the "Link" icon in room list for this room.',
		)) . '

					<hr class="formRowSep" />

					' . $__templater->formNumberBoxRow(array(
			'name' => 'room_max_users',
			'value' => $__vars['room']['room_max_users'],
			'min' => '0',
			'units' => 'Users',
		), array(
			'label' => 'Maximum users',
			'explain' => 'The maximum number of users than can join the room. Set to 0 for unlimited users.',
		)) . '
					
					<hr class="formRowSep" />

					' . $__templater->formNumberBoxRow(array(
			'name' => 'room_flood',
			'value' => $__vars['room']['room_flood'],
			'min' => '0',
			'units' => 'Seconds',
		), array(
			'label' => 'Minimum time between messages',
			'explain' => 'Users will have to wait this many seconds between posting messages. Users with the permission "Can bypass flood check" will be exempt from this option.',
		)) . '

					<hr class="formRowSep" />

					' . $__templater->formNumberBoxRow(array(
			'name' => 'room_prune',
			'value' => $__vars['room']['room_prune'],
			'min' => '0',
			'units' => 'Hours',
		), array(
			'label' => 'Auto-prune messages every',
			'explain' => 'This option allows you to auto-prune messages every x hours. Set to 0 to disable it.',
		)) . '

					<hr class="formRowSep" />

					' . $__templater->formTextBoxRow(array(
			'name' => 'room_thread_id',
			'value' => $__vars['room']['room_thread_id'],
		), array(
			'label' => 'Thread ID',
			'explain' => 'If a thread ID is set, messages posted in this room will be posted in that thread as well.',
		)) . '

					' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'room_thread_reply',
			'value' => '1',
			'checked' => ($__vars['room']['room_thread_reply'] == true),
			'label' => 'Enable thread reply',
			'_type' => 'option',
		)), array(
			'explain' => 'This option allows you to post thread replies into the room.',
		)) . '

					<hr class="formRowSep" />

					';
		$__compilerTemp6 = $__templater->mergeChoiceOptions(array(), $__vars['rooms']);
		$__compilerTemp3 .= $__templater->formSelectRow(array(
			'name' => 'room_child_ids',
			'value' => $__vars['room']['room_child_ids'],
			'multiple' => 'true',
		), $__compilerTemp6, array(
			'label' => 'Repost in selected rooms',
			'explain' => 'This option allows you to automatically repost the messages you post in this room in other rooms.',
		)) . '
				</div>
			';
	}
	$__compilerTemp7 = '';
	if ($__templater->method($__vars['room'], 'isInsert', array())) {
		$__compilerTemp7 .= '
					' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'name' => 'join_room',
			'value' => '1',
			'label' => 'Join room',
			'checked' => 'checked',
			'_type' => 'option',
		))) . '
				';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'room_name',
		'value' => $__vars['room']['room_name'],
		'maxlength' => $__templater->func('max_length', array($__vars['room'], 'room_name', ), false),
	), array(
		'label' => 'Room name',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'room_description',
		'value' => $__vars['room']['room_description'],
		'maxlength' => $__templater->func('max_length', array($__vars['room'], 'room_description', ), false),
		'rows' => '3',
	), array(
		'label' => 'Room description',
	)) . '

			' . $__compilerTemp1 . '

			' . $__compilerTemp2 . '

			' . $__compilerTemp3 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
		'class' => 'js-overlayClose',
	), array(
		'rowtype' => 'simple',
		'html' => '
				' . $__compilerTemp7 . '
			',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('chat/room/save', $__vars['room'], ), false),
		'class' => 'block siropuChatRoomEdit',
		'ajax' => 'true',
		'data-xf-init' => 'siropu-chat-room-save',
	));
	return $__finalCompiled;
}
);