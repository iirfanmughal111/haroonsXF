<?php
// FROM HASH: 4f3fab0743fec3bd72066f2667a67c97
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Commands');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add command', array(
		'href' => $__templater->func('link', array('chat/commands/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['commands'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['commands'])) {
			foreach ($__vars['commands'] AS $__vars['command']) {
				$__compilerTemp1 .= '
						' . $__templater->dataRow(array(
					'delete' => $__templater->func('link', array('chat/commands/delete', $__vars['command'], ), false),
				), array(array(
					'href' => $__templater->func('link', array('chat/commands/edit', $__vars['command'], ), false),
					'_type' => 'cell',
					'html' => '
								' . $__templater->escape($__vars['command']['command_name']) . '
							',
				),
				array(
					'name' => 'command_enabled[' . $__vars['command']['command_name'] . ']',
					'selected' => $__vars['command']['command_enabled'],
					'class' => 'dataList-cell--separated',
					'submit' => 'true',
					'tooltip' => 'Enable / disable command',
					'_type' => 'toggle',
					'html' => '',
				))) . '
					';
			}
		}
		$__finalCompiled .= $__templater->form('
		<div class="block-outer">
			' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => 'chat/commands',
			'class' => 'block-outer-opposite',
		), $__vars) . '
		</div>
		<div class="block-container">
			<div class="block-body">
				' . $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), array(array(
			'_type' => 'cell',
			'html' => 'Command name',
		),
		array(
			'_type' => 'cell',
			'html' => '&nbsp;',
		),
		array(
			'_type' => 'cell',
			'html' => '&nbsp;',
		))) . '
					' . $__compilerTemp1 . '
				', array(
		)) . '
			</div>
			<div class="block-footer">
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['commands'], ), true) . '</span>
			</div>
		</div>
	', array(
			'action' => $__templater->func('link', array('chat/commands/toggle', ), false),
			'class' => 'block',
			'ajax' => 'true',
		)) . '
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'No items have been created yet.' . '</div>
';
	}
	return $__finalCompiled;
}
);