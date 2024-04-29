<?php
// FROM HASH: b4e78d595ccf27521d95254b8cf9f83c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Bot messages');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add bot message', array(
		'href' => $__templater->func('link', array('chat/bot-messages/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['messages'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['messages'])) {
			foreach ($__vars['messages'] AS $__vars['message']) {
				$__compilerTemp1 .= '
						' . $__templater->dataRow(array(
					'delete' => $__templater->func('link', array('chat/bot-messages/delete', $__vars['message'], ), false),
				), array(array(
					'href' => $__templater->func('link', array('chat/bot-messages/edit', $__vars['message'], ), false),
					'_type' => 'cell',
					'html' => '
								' . $__templater->escape($__vars['message']['message_title']) . '
							',
				),
				array(
					'_type' => 'cell',
					'html' => '
								' . $__templater->escape($__vars['message']['next_run']) . '
							',
				),
				array(
					'name' => 'message_enabled[' . $__vars['message']['message_id'] . ']',
					'selected' => $__vars['message']['message_enabled'],
					'class' => 'dataList-cell--separated',
					'submit' => 'true',
					'tooltip' => 'Enable / disable bot message',
					'_type' => 'toggle',
					'html' => '',
				))) . '
					';
			}
		}
		$__finalCompiled .= $__templater->form('
		<div class="block-outer">
			' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => 'chat/bot-messages',
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
			'html' => 'Title',
		),
		array(
			'_type' => 'cell',
			'html' => 'Post at',
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
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['messages'], ), true) . '</span>
			</div>
		</div>
	', array(
			'action' => $__templater->func('link', array('chat/bot-messages/toggle', ), false),
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