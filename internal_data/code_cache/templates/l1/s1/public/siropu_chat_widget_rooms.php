<?php
// FROM HASH: 91b173b52c9f92d7abac87381c23df5b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('siropu_chat_widget.less');
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'siropu/chat/widget.js',
		'min' => 'true',
	));
	$__finalCompiled .= '

<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
	<div class="block-container">
		<h3 class="block-minorHeader">' . ($__templater->escape($__vars['title']) ?: 'Chat rooms') . '</h3>
		<div class="block-body siropuChatWidget siropuChatWidgetRooms">
			';
	if ($__vars['options']['search'] AND ($__templater->func('count', array($__vars['rooms'], ), false) >= $__vars['options']['search'])) {
		$__finalCompiled .= '
				<div class="block-row">
					<input type="search" class="input siropuChatFindItem" autocomplete="off" placeholder="' . 'Find room...' . '" data-xf-init="siropu-chat-find-item">
				</div>
			';
	}
	$__finalCompiled .= '
			<ul>
				';
	if ($__templater->isTraversable($__vars['rooms'])) {
		foreach ($__vars['rooms'] AS $__vars['room']) {
			$__finalCompiled .= '
					';
			$__vars['userCount'] = $__templater->filter($__templater->func('count', array($__vars['users'][$__vars['room']['room_id']], ), false), array(array('number', array()),), false);
			$__finalCompiled .= '
					<li class="siropuChat' . ($__vars['userCount'] ? 'Active' : 'Inactive') . 'Room" data-id="' . $__templater->escape($__vars['room']['room_id']) . '" data-name="' . $__templater->filter($__vars['room']['room_name'], array(array('for_attr', array()),), true) . '" data-xf-init="siropu-chat-widget-room">
						' . $__templater->escape($__vars['room']['room_name']) . ' ' . ($__templater->method($__vars['room'], 'isJoined', array()) ? '&#10004;' : '') . '
						<span>' . $__templater->escape($__vars['userCount']) . ' ' . $__templater->fontAwesome('fas fa-users', array(
			)) . '</span>
						';
			if ($__vars['xf']['visitor']['user_id']) {
				$__finalCompiled .= '
							';
				if ($__templater->method($__vars['room'], 'canJoin', array(null, false, )) AND (!$__templater->method($__vars['room'], 'isJoined', array()))) {
					$__finalCompiled .= '
								<a href="' . $__templater->func('link', array('chat/room', $__vars['room'], ), true) . '" class="button" title="' . $__templater->filter('Join room', array(array('for_attr', array()),), true) . '" data-action="join">' . $__templater->fontAwesome('fas fa-sign-in', array(
					)) . '</a>
							';
				}
				$__finalCompiled .= '
							';
				if ($__templater->method($__vars['room'], 'isJoined', array()) AND $__templater->method($__vars['room'], 'canLeave', array())) {
					$__finalCompiled .= '
								<a href="' . $__templater->func('link', array('chat/room/leave', $__vars['room'], ), true) . '" class="button button--cta" title="' . $__templater->filter('Leave room', array(array('for_attr', array()),), true) . '" data-action="leave">' . $__templater->fontAwesome('fas fa-sign-out', array(
					)) . '</a>
							';
				}
				$__finalCompiled .= '
						';
			}
			$__finalCompiled .= '
						';
			if ($__vars['userCount']) {
				$__finalCompiled .= '
							<ul>
								';
				if ($__templater->isTraversable($__vars['users'][$__vars['room']['room_id']])) {
					foreach ($__vars['users'][$__vars['room']['room_id']] AS $__vars['user']) {
						$__finalCompiled .= '
									<li data-user-id="' . $__templater->escape($__vars['user']['user_id']) . '">
										';
						if ($__vars['user']['user_id']) {
							$__finalCompiled .= '
											' . $__templater->func('avatar', array($__vars['user'], 'xxs', false, array(
								'defaultname' => 'Unknown',
								'itemprop' => 'image',
							))) . '
											' . $__templater->func('username_link', array($__vars['user'], true, array(
								'defaultname' => 'Unknown',
							))) . '
										';
						} else {
							$__finalCompiled .= '
											' . $__templater->func('avatar', array($__vars['user'], 'xxs', false, array(
								'defaultname' => 'Guest',
								'itemprop' => 'image',
								'href' => '',
							))) . '
											' . $__templater->callMacro('siropu_chat_guest_macros', 'username', array(
								'user' => $__vars['user'],
							), $__vars) . '
										';
						}
						$__finalCompiled .= '
										' . $__templater->callMacro('siropu_chat_user_macros', 'activity_status', array(
							'user' => $__vars['user'],
							'roomId' => $__vars['room']['room_id'],
						), $__vars) . '
									</li>
								';
					}
				}
				$__finalCompiled .= '
							</ul>
						';
			}
			$__finalCompiled .= '
					</li>
				';
		}
	}
	$__finalCompiled .= '
			</ul>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);