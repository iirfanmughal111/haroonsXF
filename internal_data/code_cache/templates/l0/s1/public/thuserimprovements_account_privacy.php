<?php
// FROM HASH: 1a5d69cfb3f4520b5b01334ea7286748
return array(
'macros' => array('privacy_option' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'user' => '!',
		'name' => '!',
		'label' => '!',
		'hideEveryone' => false,
		'hideFollowed' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<dl class="inputLabelPair">
		<dt>' . $__templater->escape($__vars['label']) . '</dt>
		<dd>
			';
	$__compilerTemp1 = array();
	if (!$__vars['hideEveryone']) {
		$__compilerTemp1[] = array(
			'value' => 'everyone',
			'label' => 'All visitors',
			'_type' => 'option',
		);
	}
	$__compilerTemp1[] = array(
		'value' => 'members',
		'label' => 'Members only',
		'_type' => 'option',
	);
	if (!$__vars['hideFollowed']) {
		$__compilerTemp1[] = array(
			'value' => 'followed',
			'label' => 'People you follow',
			'_type' => 'option',
		);
	}
	$__compilerTemp1[] = array(
		'value' => 'none',
		'label' => 'Nobody',
		'_type' => 'option',
	);
	$__finalCompiled .= $__templater->formSelect(array(
		'name' => 'privacy[' . $__vars['name'] . ']',
		'value' => $__vars['user']['Privacy'][$__vars['name']],
	), $__compilerTemp1) . '
		</dd>
	</dl>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('klUI', 'klUIManageUsernamePrivacy', ))) {
		$__finalCompiled .= '
	' . $__templater->callMacro(null, 'privacy_option', array(
			'user' => $__vars['xf']['visitor'],
			'name' => 'th_view_username_changes',
			'label' => 'View your username change history' . $__vars['xf']['language']['label_separator'],
		), $__vars) . '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('klUI', 'klUIManageStatsPrivacy', ))) {
		$__finalCompiled .= '
	' . $__templater->callMacro(null, 'privacy_option', array(
			'user' => $__vars['xf']['visitor'],
			'name' => 'th_view_profile_stats',
			'label' => 'View your profile stats bar' . $__vars['xf']['language']['label_separator'],
		), $__vars) . '
';
	}
	$__finalCompiled .= '

';
	if ($__vars['xf']['options']['klUiProfileViews']) {
		$__finalCompiled .= '
	' . $__templater->callMacro(null, 'privacy_option', array(
			'user' => $__vars['xf']['visitor'],
			'name' => 'th_view_profile_views',
			'label' => 'View your profile view counter' . $__vars['xf']['language']['label_separator'],
		), $__vars) . '
';
	}
	$__finalCompiled .= '


';
	return $__finalCompiled;
}
);