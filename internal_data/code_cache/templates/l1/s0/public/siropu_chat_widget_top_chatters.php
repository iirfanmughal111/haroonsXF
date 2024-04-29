<?php
// FROM HASH: 30dd3f3516532a9e3daa0ccf89258f3f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('siropu_chat_widget.less');
	$__finalCompiled .= '

<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
	<div class="block-container">
		<h3 class="block-minorHeader">' . ($__templater->escape($__vars['title']) ?: 'Top chatters') . '</h3>
		<div class="block-body block-row siropuChatWidget' . ($__vars['options']['grid'] ? ' siropuChatWidgetGrid' : '') . ' siropuChatWidgetTopChatters">
			<ul class="' . ($__vars['options']['grid'] ? 'listHeap' : '') . '">
				';
	if ($__templater->func('count', array($__vars['users'], ), false)) {
		$__finalCompiled .= '
					';
		if ($__templater->isTraversable($__vars['users'])) {
			foreach ($__vars['users'] AS $__vars['user']) {
				$__finalCompiled .= '
						<li title="' . $__templater->escape($__vars['user']['siropu_chat_message_count']) . ' ' . 'Messages' . '">
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
								<span class="siropuChatUserMessageCount">' . $__templater->escape($__vars['user']['siropu_chat_message_count']) . '</span>
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
					<li>' . 'There are no chatters in the top.' . '</li>
				';
	}
	$__finalCompiled .= '
			</ul>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);