<?php
// FROM HASH: 62870bd1b982db47741d2faece457e99
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-row block-row--separated">
	' . $__templater->func('bb_code', array($__vars['report']['content_info']['message']['text'], 'siropu_chat_conv_message', $__vars['report']['User'], ), true) . '
</div>';
	return $__finalCompiled;
}
);