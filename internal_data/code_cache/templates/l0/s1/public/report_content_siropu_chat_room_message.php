<?php
// FROM HASH: 86b8cec0f200292b7617136a38a18d10
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-row block-row--separated">
	' . $__templater->func('bb_code', array($__vars['report']['content_info']['message']['text'], 'siropu_chat_room_message', $__vars['report']['User'], ), true) . '
</div>
<div class="block-row block-row--separated block-row--minor">
	<dl class="pairs pairs--inline">
		<dt>' . 'Room' . '</dt>
		<dd><a href="' . $__templater->func('link', array('chat/room', $__vars['report']['content_info'], ), true) . '">' . $__templater->escape($__vars['report']['content_info']['room_name']) . '</a></dd>
	</dl>
</div>';
	return $__finalCompiled;
}
);