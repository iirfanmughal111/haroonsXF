<?php
// FROM HASH: 49d9a521989ffecb5a4f0dbc7ac57ebb
return array(
'macros' => array('ads' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'position' => '!',
		'ads' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (!$__templater->test($__vars['ads'], 'empty', array())) {
		$__finalCompiled .= '
		<div class="siropuChatAds" data-position="' . $__templater->escape($__vars['position']) . '">
			' . $__templater->filter($__vars['ads'], array(array('raw', array()),), true) . '
			';
		if ($__templater->method($__vars['xf']['visitor'], 'canEditSiropuChatAds', array())) {
			$__finalCompiled .= ' <a href="' . $__templater->func('link', array('chat/edit-ads', ), true) . '" data-xf-click="overlay" data-cache="false" style="font-size: 10px;">' . 'Edit' . '</a>';
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