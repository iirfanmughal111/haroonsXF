<?php
// FROM HASH: a6ca050195401f057e8235ba7ade10d7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Chat');
	$__finalCompiled .= '
';
	$__templater->pageParams['noH1'] = true;
	$__finalCompiled .= '

';
	if ($__vars['chat']['isFullPage']) {
		$__finalCompiled .= '
	';
		$__templater->setPageParam('template', 'SIROPU_CHAT_CONTAINER');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->widgetPosition('siropu_chat_page', array(
		'position' => 'chat_page',
		'params' => $__vars['chat'],
	)) . '
';
	$__templater->modifySidebarHtml('_xfWidgetPositionSidebar11ae08eade6c0640971726e90d8c7f55', $__templater->widgetPosition('siropu_chat_page_sidebar', array()), 'replace');
	return $__finalCompiled;
}
);