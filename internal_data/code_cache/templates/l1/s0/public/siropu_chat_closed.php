<?php
// FROM HASH: 99e0be9a2fc61f2677b4f28083a8deb9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('siropu_chat_disabled.less');
	$__finalCompiled .= '

<div id="siropuChat" class="block-container ' . $__templater->escape($__vars['cssClass']) . '">
	<div class="block-header">
		' . $__templater->fontAwesome('fa-comments', array(
	)) . ' ' . 'Chat' . '
	</div>
	<div class="block-body block-row">
		' . $__templater->escape($__vars['message']) . '
	</div>
</div>';
	return $__finalCompiled;
}
);