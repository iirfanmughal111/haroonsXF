<?php
// FROM HASH: 2f033e699c8dd61bdd4b14f8acc5371a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<style>
	.limit-info{margin-right: 10px;}
</style>

';
	$__vars['dailyPostLimit'] = $__templater->method($__vars['xf']['visitor'], 'hasPermission', array('fs_limitations', 'fs_dailyDiscussiontLimit', ));
	$__finalCompiled .= '
';
	$__vars['visitorDailyPostCount'] = $__vars['xf']['visitor']['daily_discussion_count'];
	$__finalCompiled .= '

';
	$__vars['upgradeUrl'] = (($__vars['xf']['visitor']['user_id'] == 1) ? $__templater->func('link', array('account-upgrade/admirer', ), false) : $__templater->func('link', array('account-upgrade/companion', ), false));
	$__finalCompiled .= '

';
	if ($__vars['dailyPostLimit'] == 0) {
		$__finalCompiled .= '
	<span class="limit-info">' . 'Discussion not allowed. Please <a href="' . $__templater->escape($__vars['upgradeUrl']) . '"> upgrade </a> your account.' . '</span>
';
	} else if ($__vars['dailyPostLimit'] > 0) {
		$__finalCompiled .= '
	';
		$__vars['remianingCount'] = ($__vars['dailyPostLimit'] - $__vars['visitorDailyPostCount']);
		$__finalCompiled .= '
	<span class="limit-info">' . 'Your daily discussion post limit: <b>( ' . $__templater->escape($__vars['visitorDailyPostCount']) . ' / ' . $__templater->escape($__vars['dailyPostLimit']) . ' )</b> .&nbsp;&nbsp;
Remaining:  <b>' . $__templater->escape($__vars['remianingCount']) . '</b>' . '</span>
';
	} else {
		$__finalCompiled .= '
	<span class="limit-info">' . 'Your daily discussion post limit: <b>Unlimited</b>' . '</span>
';
	}
	return $__finalCompiled;
}
);