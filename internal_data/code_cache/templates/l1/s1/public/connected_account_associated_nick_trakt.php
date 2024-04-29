<?php
// FROM HASH: 8db09e51b4048cc4dc5f0d910b5b7cbb
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
<div>
	' . ($__templater->escape($__vars['providerData']['username']) ?: 'th_cap_unknown_account') . '
</div>';
	return $__finalCompiled;
}
);