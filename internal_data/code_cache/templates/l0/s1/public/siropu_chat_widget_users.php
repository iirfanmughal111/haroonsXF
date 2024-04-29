<?php
// FROM HASH: e7f619157b01048d9b6c8be940595f18
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('siropu_chat_widget.less');
	$__finalCompiled .= '

<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
	<div class="block-container">
		<h3 class="block-minorHeader">' . ($__templater->escape($__vars['title']) ?: 'Chat users') . '</h3>
		<div class="block-body block-row siropuChatWidget siropuChatWidgetUsers' . ($__vars['options']['grid'] ? ' siropuChatWidgetGrid' : '') . '">
			';
	if ($__vars['options']['search'] AND ($__templater->func('count', array($__vars['users'], ), false) >= $__vars['options']['search'])) {
		$__finalCompiled .= '
				';
		$__templater->includeJs(array(
			'src' => 'siropu/chat/widget.js',
			'min' => 'true',
		));
		$__finalCompiled .= '
				<input type="search" class="input siropuChatFindItem" autocomplete="off" placeholder="' . 'Find user...' . '" data-xf-init="siropu-chat-find-item">
			';
	}
	$__finalCompiled .= '
			<ul class="' . ($__vars['options']['grid'] ? 'listHeap' : '') . '">
				';
	if ($__templater->func('count', array($__vars['users'], ), false)) {
		$__finalCompiled .= '
					';
		if ($__templater->isTraversable($__vars['users'])) {
			foreach ($__vars['users'] AS $__vars['user']) {
				$__finalCompiled .= '
						<li data-name="' . $__templater->filter($__vars['user']['username'], array(array('for_attr', array()),), true) . '" title="' . $__templater->filter('Last activity', array(array('for_attr', array()),), true) . ': ' . $__templater->func('date_time', array($__vars['user']['siropu_chat_last_activity'], ), true) . '">
							';
				if ($__vars['options']['grid']) {
					$__finalCompiled .= '
								' . $__templater->func('avatar', array($__vars['user'], 'xs', false, array(
						'defaultname' => 'Unknown',
						'itemprop' => 'image',
					))) . '
							';
				} else {
					$__finalCompiled .= '
								';
					if ($__vars['options']['avatar']) {
						$__finalCompiled .= '
									' . $__templater->func('avatar', array($__vars['user'], 'xxs', false, array(
							'defaultname' => 'Unknown',
							'itemprop' => 'image',
						))) . '
								';
					}
					$__finalCompiled .= '
								' . $__templater->func('username_link', array($__vars['user'], true, array(
						'defaultname' => 'Unknown',
					))) . '
								' . $__templater->callMacro('siropu_chat_user_macros', 'activity_status', array(
						'user' => $__vars['user'],
						'roomId' => null,
					), $__vars) . '
							';
				}
				$__finalCompiled .= '
						</li>
					';
			}
		}
		$__finalCompiled .= '
				';
	} else {
		$__finalCompiled .= '
					<li>' . 'No one is chatting at the moment.' . '</li>
				';
	}
	$__finalCompiled .= '
			</ul>
		</div>
		';
	if ($__templater->func('count', array($__vars['users'], ), false) >= 10) {
		$__finalCompiled .= '
			<div class="block-footer">
				<span class="block-footer-counter">' . 'Total' . ': ' . $__templater->func('count', array($__vars['users'], ), true) . '</span>
			</div>
		';
	}
	$__finalCompiled .= '
	</div>
</div>';
	return $__finalCompiled;
}
);