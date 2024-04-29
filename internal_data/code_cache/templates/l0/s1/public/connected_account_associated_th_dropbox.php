<?php
// FROM HASH: 7cab3ae20e2d138d957445aeb5ab5771
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['providerData']['avatar_url']) {
		$__finalCompiled .= '
	<img src="' . $__templater->escape($__vars['providerData']['avatar_url']) . '" width="48" alt="" />
';
	}
	$__finalCompiled .= '

<div>' . ($__templater->escape($__vars['providerData']['username']) ?: 'Unknown account') . '</div>';
	return $__finalCompiled;
}
);