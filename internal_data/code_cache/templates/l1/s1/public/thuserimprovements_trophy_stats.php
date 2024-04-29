<?php
// FROM HASH: ce5465ff74c226b1f5e1d29f3739e30b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['title']));
	$__finalCompiled .= '

<div class="block">
	';
	if (!$__templater->test($__vars['users'], 'empty', array())) {
		$__finalCompiled .= '
		<div class="block-container">
			<ol class="block-body">
				';
		if ($__templater->isTraversable($__vars['users'])) {
			foreach ($__vars['users'] AS $__vars['userId'] => $__vars['user']) {
				$__finalCompiled .= '
					<li class="block-row block-row--separated">
						' . $__templater->callMacro('member_list_macros', 'item', array(
					'user' => ($__vars['userKey'] ? $__vars['user'][$__vars['userKey']] : $__vars['user']),
					'extraData' => $__vars['user'][$__vars['extraData']],
					'extraDataBig' => true,
				), $__vars) . '
					</li>
				';
			}
		}
		$__finalCompiled .= '
			</ol>
		</div>
	';
	} else {
		$__finalCompiled .= '
		<div class="block-row">' . 'No users match the specified criteria.' . '</div>
	';
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);