<?php
// FROM HASH: 7b920a581be2379ab05fd3dc6af9d9e4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Bot responses');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add bot response', array(
		'href' => $__templater->func('link', array('chat/bot-responses/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['responses'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['responses'])) {
			foreach ($__vars['responses'] AS $__vars['response']) {
				$__compilerTemp1 .= '
						' . $__templater->dataRow(array(
					'delete' => $__templater->func('link', array('chat/bot-responses/delete', $__vars['response'], ), false),
				), array(array(
					'href' => $__templater->func('link', array('chat/bot-responses/edit', $__vars['response'], ), false),
					'_type' => 'cell',
					'html' => '
								' . $__templater->escape($__vars['response']['response_keyword']) . '
							',
				),
				array(
					'_type' => 'cell',
					'html' => '
								' . $__templater->filter($__vars['response']['response_bot_name'], array(array('raw', array()),), true) . '
							',
				),
				array(
					'name' => 'response_enabled[' . $__vars['response']['response_id'] . ']',
					'selected' => $__vars['response']['response_enabled'],
					'class' => 'dataList-cell--separated',
					'submit' => 'true',
					'tooltip' => 'Enable / disable bot response',
					'_type' => 'toggle',
					'html' => '',
				))) . '
					';
			}
		}
		$__finalCompiled .= $__templater->form('
		<div class="block-outer">
			' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => 'chat/bot-responses',
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
			'html' => 'Keyword',
		),
		array(
			'_type' => 'cell',
			'html' => 'Bot name',
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
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['responses'], ), true) . '</span>
			</div>
		</div>
	', array(
			'action' => $__templater->func('link', array('chat/bot-responses/toggle', ), false),
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