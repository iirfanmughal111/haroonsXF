<?php
// FROM HASH: c8bad21629d32a22c930afd29dddce46
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Message likes');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<ol class="block-body">
			';
	$__compilerTemp1 = true;
	if ($__templater->isTraversable($__vars['users'])) {
		foreach ($__vars['users'] AS $__vars['user']) {
			$__compilerTemp1 = false;
			$__finalCompiled .= '
				<li class="block-row block-row--separated">
					' . $__templater->callMacro('member_list_macros', 'item', array(
				'user' => $__vars['user'],
				'extraData' => $__templater->func('date_time', array($__templater->method($__vars['message'], 'getUserLikeDate', array($__vars['user']['user_id'], )), ), false),
			), $__vars) . '
				</li>
			';
		}
	}
	if ($__compilerTemp1) {
		$__finalCompiled .= '
				' . 'This message doesn\'t have any likes yet.' . '
			';
	}
	$__finalCompiled .= '
		</ol>
	</div>
</div>';
	return $__finalCompiled;
}
);