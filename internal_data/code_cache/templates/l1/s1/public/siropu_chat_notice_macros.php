<?php
// FROM HASH: 74d96673c7253e5aed0b247f438ed728
return array(
'macros' => array('notice' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'notice' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (!$__templater->test($__vars['notice'], 'empty', array())) {
		$__finalCompiled .= '
		<div id="siropuChatNotice">
			' . $__templater->fontAwesome('fa-bullhorn', array(
		)) . ' <span>' . $__templater->func('bb_code', array($__vars['notice'], 'siropu_chat', null, ), true) . '</span> ';
		if ($__templater->method($__vars['xf']['visitor'], 'canEditSiropuChatNotice', array())) {
			$__finalCompiled .= '<a href="' . $__templater->func('link', array('chat/edit-notice', ), true) . '" data-xf-click="overlay" data-cache="false">' . 'Edit' . '</a>';
		}
		$__finalCompiled .= '
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);