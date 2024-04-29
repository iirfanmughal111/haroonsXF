<?php
// FROM HASH: 87d85a1792ceb6f55c287fa80b67d9e5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['message'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add bot message');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit bot message' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['message']['message_title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['message'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('chat/bot-messages/delete', $__vars['message'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['rooms'])) {
		foreach ($__vars['rooms'] AS $__vars['room']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['room']['room_id'],
				'label' => $__templater->escape($__vars['room']['room_name']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('Please make sure that <a href="' . $__templater->func('link', array('options/groups/basicBoard/#guestTimeZone', ), true) . '" target="_blank">guests\' time zone</a> corresponds to your time zone.', array(
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'message_title',
		'value' => $__vars['message']['message_title'],
		'maxlength' => $__templater->func('max_length', array($__vars['message'], 'message_title', ), false),
	), array(
		'label' => 'Title',
	)) . '
	
			' . $__templater->formTextAreaRow(array(
		'name' => 'message_text',
		'value' => $__vars['message']['message_text'],
		'rows' => '3',
	), array(
		'label' => 'Message',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'message_bot_name',
		'value' => $__vars['message']['message_bot_name'],
		'maxlength' => $__templater->func('max_length', array($__vars['message'], 'message_bot_name', ), false),
	), array(
		'label' => 'Bot name',
		'explain' => 'The name of the bot that will return the response.',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'message_rooms',
		'value' => $__vars['message']['message_rooms'],
		'multiple' => 'true',
	), $__compilerTemp1, array(
		'label' => 'Enable for selected rooms',
		'explain' => 'If no rooms are selected, the bot message will be posted in all rooms.',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRadioRow(array(
		'name' => 'message_rules[day_type]',
		'value' => ($__vars['message']['message_rules']['day_type'] ?: 'dow'),
	), array(array(
		'value' => 'date',
		'label' => 'Date' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formDateInput(array(
		'name' => 'message_rules[date]',
		'value' => $__vars['message']['message_rules']['date'],
	))),
		'_type' => 'option',
	),
	array(
		'value' => 'dow',
		'label' => 'Day of the week' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formSelect(array(
		'name' => 'message_rules[dow]',
		'value' => $__templater->filter($__vars['message']['message_rules']['dow'], array(array('raw', array()),), false),
		'multiple' => 'true',
		'size' => '8',
		'style' => 'width: 200px',
	), array(array(
		'value' => '-1',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'label' => 'Sunday',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => 'Monday',
		'_type' => 'option',
	),
	array(
		'value' => '2',
		'label' => 'Tuesday',
		'_type' => 'option',
	),
	array(
		'value' => '3',
		'label' => 'Wednesday',
		'_type' => 'option',
	),
	array(
		'value' => '4',
		'label' => 'Thursday',
		'_type' => 'option',
	),
	array(
		'value' => '5',
		'label' => 'Friday',
		'_type' => 'option',
	),
	array(
		'value' => '6',
		'label' => 'Saturday',
		'_type' => 'option',
	)))),
		'_type' => 'option',
	)), array(
		'label' => 'Post at',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'message_rules[hours]',
		'value' => $__templater->filter($__vars['message']['message_rules']['hours'], array(array('raw', array()),), false),
		'multiple' => 'true',
		'size' => '8',
		'style' => 'width: 200px',
	), array(array(
		'value' => '-1',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'label' => '0 ' . $__vars['xf']['language']['parenthesis_open'] . 'Midnight' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => '1',
		'_type' => 'option',
	),
	array(
		'value' => '2',
		'label' => '2',
		'_type' => 'option',
	),
	array(
		'value' => '3',
		'label' => '3',
		'_type' => 'option',
	),
	array(
		'value' => '4',
		'label' => '4',
		'_type' => 'option',
	),
	array(
		'value' => '5',
		'label' => '5',
		'_type' => 'option',
	),
	array(
		'value' => '6',
		'label' => '6',
		'_type' => 'option',
	),
	array(
		'value' => '7',
		'label' => '7',
		'_type' => 'option',
	),
	array(
		'value' => '8',
		'label' => '8',
		'_type' => 'option',
	),
	array(
		'value' => '9',
		'label' => '9',
		'_type' => 'option',
	),
	array(
		'value' => '10',
		'label' => '10',
		'_type' => 'option',
	),
	array(
		'value' => '11',
		'label' => '11',
		'_type' => 'option',
	),
	array(
		'value' => '12',
		'label' => '12 ' . $__vars['xf']['language']['parenthesis_open'] . 'Noon' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	),
	array(
		'value' => '13',
		'label' => '13',
		'_type' => 'option',
	),
	array(
		'value' => '14',
		'label' => '14',
		'_type' => 'option',
	),
	array(
		'value' => '15',
		'label' => '15',
		'_type' => 'option',
	),
	array(
		'value' => '16',
		'label' => '16',
		'_type' => 'option',
	),
	array(
		'value' => '17',
		'label' => '17',
		'_type' => 'option',
	),
	array(
		'value' => '18',
		'label' => '18',
		'_type' => 'option',
	),
	array(
		'value' => '19',
		'label' => '19',
		'_type' => 'option',
	),
	array(
		'value' => '20',
		'label' => '20',
		'_type' => 'option',
	),
	array(
		'value' => '21',
		'label' => '21',
		'_type' => 'option',
	),
	array(
		'value' => '22',
		'label' => '22',
		'_type' => 'option',
	),
	array(
		'value' => '23',
		'label' => '23',
		'_type' => 'option',
	)), array(
		'label' => 'Hour',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'message_rules[minutes]',
		'value' => $__templater->filter($__vars['message']['message_rules']['minutes'], array(array('raw', array()),), false),
		'multiple' => 'true',
		'size' => '8',
		'style' => 'width: 200px',
	), array(array(
		'value' => '0',
		'label' => '0',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => '1',
		'_type' => 'option',
	),
	array(
		'value' => '2',
		'label' => '2',
		'_type' => 'option',
	),
	array(
		'value' => '3',
		'label' => '3',
		'_type' => 'option',
	),
	array(
		'value' => '4',
		'label' => '4',
		'_type' => 'option',
	),
	array(
		'value' => '5',
		'label' => '5',
		'_type' => 'option',
	),
	array(
		'value' => '6',
		'label' => '6',
		'_type' => 'option',
	),
	array(
		'value' => '7',
		'label' => '7',
		'_type' => 'option',
	),
	array(
		'value' => '8',
		'label' => '8',
		'_type' => 'option',
	),
	array(
		'value' => '9',
		'label' => '9',
		'_type' => 'option',
	),
	array(
		'value' => '10',
		'label' => '10',
		'_type' => 'option',
	),
	array(
		'value' => '11',
		'label' => '11',
		'_type' => 'option',
	),
	array(
		'value' => '12',
		'label' => '12',
		'_type' => 'option',
	),
	array(
		'value' => '13',
		'label' => '13',
		'_type' => 'option',
	),
	array(
		'value' => '14',
		'label' => '14',
		'_type' => 'option',
	),
	array(
		'value' => '15',
		'label' => '15',
		'_type' => 'option',
	),
	array(
		'value' => '16',
		'label' => '16',
		'_type' => 'option',
	),
	array(
		'value' => '17',
		'label' => '17',
		'_type' => 'option',
	),
	array(
		'value' => '18',
		'label' => '18',
		'_type' => 'option',
	),
	array(
		'value' => '19',
		'label' => '19',
		'_type' => 'option',
	),
	array(
		'value' => '20',
		'label' => '20',
		'_type' => 'option',
	),
	array(
		'value' => '21',
		'label' => '21',
		'_type' => 'option',
	),
	array(
		'value' => '22',
		'label' => '22',
		'_type' => 'option',
	),
	array(
		'value' => '23',
		'label' => '23',
		'_type' => 'option',
	),
	array(
		'value' => '24',
		'label' => '24',
		'_type' => 'option',
	),
	array(
		'value' => '25',
		'label' => '25',
		'_type' => 'option',
	),
	array(
		'value' => '26',
		'label' => '26',
		'_type' => 'option',
	),
	array(
		'value' => '27',
		'label' => '27',
		'_type' => 'option',
	),
	array(
		'value' => '28',
		'label' => '28',
		'_type' => 'option',
	),
	array(
		'value' => '29',
		'label' => '29',
		'_type' => 'option',
	),
	array(
		'value' => '30',
		'label' => '30',
		'_type' => 'option',
	),
	array(
		'value' => '31',
		'label' => '31',
		'_type' => 'option',
	),
	array(
		'value' => '32',
		'label' => '32',
		'_type' => 'option',
	),
	array(
		'value' => '33',
		'label' => '33',
		'_type' => 'option',
	),
	array(
		'value' => '34',
		'label' => '34',
		'_type' => 'option',
	),
	array(
		'value' => '35',
		'label' => '35',
		'_type' => 'option',
	),
	array(
		'value' => '36',
		'label' => '36',
		'_type' => 'option',
	),
	array(
		'value' => '37',
		'label' => '37',
		'_type' => 'option',
	),
	array(
		'value' => '38',
		'label' => '38',
		'_type' => 'option',
	),
	array(
		'value' => '39',
		'label' => '39',
		'_type' => 'option',
	),
	array(
		'value' => '40',
		'label' => '40',
		'_type' => 'option',
	),
	array(
		'value' => '41',
		'label' => '41',
		'_type' => 'option',
	),
	array(
		'value' => '42',
		'label' => '42',
		'_type' => 'option',
	),
	array(
		'value' => '43',
		'label' => '43',
		'_type' => 'option',
	),
	array(
		'value' => '44',
		'label' => '44',
		'_type' => 'option',
	),
	array(
		'value' => '45',
		'label' => '45',
		'_type' => 'option',
	),
	array(
		'value' => '46',
		'label' => '46',
		'_type' => 'option',
	),
	array(
		'value' => '47',
		'label' => '47',
		'_type' => 'option',
	),
	array(
		'value' => '48',
		'label' => '48',
		'_type' => 'option',
	),
	array(
		'value' => '49',
		'label' => '49',
		'_type' => 'option',
	),
	array(
		'value' => '50',
		'label' => '50',
		'_type' => 'option',
	),
	array(
		'value' => '51',
		'label' => '51',
		'_type' => 'option',
	),
	array(
		'value' => '52',
		'label' => '52',
		'_type' => 'option',
	),
	array(
		'value' => '53',
		'label' => '53',
		'_type' => 'option',
	),
	array(
		'value' => '54',
		'label' => '54',
		'_type' => 'option',
	),
	array(
		'value' => '55',
		'label' => '55',
		'_type' => 'option',
	),
	array(
		'value' => '56',
		'label' => '56',
		'_type' => 'option',
	),
	array(
		'value' => '57',
		'label' => '57',
		'_type' => 'option',
	),
	array(
		'value' => '58',
		'label' => '58',
		'_type' => 'option',
	),
	array(
		'value' => '59',
		'label' => '59',
		'_type' => 'option',
	)), array(
		'label' => 'Minute',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('chat/bot-messages/save', $__vars['message'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);