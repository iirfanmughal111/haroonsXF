<?php
// FROM HASH: e52f6613d52ca0bd9c5d3eb3a8087d96
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__vars['data']['id']) {
		$__compilerTemp1 .= '
		' . 'Edit limitations' . ' : ' . $__templater->escape($__vars['data']['UserGroup']['title']) . ' 
		';
	} else {
		$__compilerTemp1 .= '
		' . 'Add new limitations' . '
	';
	}
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('
	' . $__compilerTemp1 . '
');
	$__finalCompiled .= '
';
	$__compilerTemp2 = '';
	if (!$__vars['data']['user_group_id']) {
		$__compilerTemp2 .= '
			
			';
		$__compilerTemp3 = array(array(
			'value' => '0',
			'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
			'_type' => 'option',
		));
		if ($__templater->isTraversable($__vars['userGroups'])) {
			foreach ($__vars['userGroups'] AS $__vars['userGroup']) {
				$__compilerTemp3[] = array(
					'value' => $__vars['userGroup']['user_group_id'],
					'selected' => ($__vars['data']['user_group_id'] == $__vars['userGroup']['user_group_id']),
					'label' => $__templater->escape($__vars['userGroup']['title']),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp2 .= $__templater->formSelectRow(array(
			'name' => 'user_group_id',
			'value' => $__vars['nodeIds'],
			'required' => 'required',
		), $__compilerTemp3, array(
			'label' => 'User group',
			'hint' => 'Required',
		)) . '
				
				';
	} else {
		$__compilerTemp2 .= '
				
				' . $__templater->formHiddenVal('user_group_id', $__vars['data']['user_group_id'], array(
		)) . '
				
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">

			<!-- User Group List -->
			
			' . $__compilerTemp2 . '
				

			' . $__templater->formTextBoxRow(array(
		'name' => 'node_ids',
		'value' => $__vars['data']['node_ids'],
		'autosize' => 'true',
		'row' => '5',
	), array(
		'label' => 'Node Ids',
		'hint' => 'Required',
		'explain' => 'Enter the FORUM_IDS Comma separated like 1,2,3,4,5,........',
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'daily_ads',
		'value' => $__vars['data']['daily_ads'],
		'autosize' => 'true',
		'row' => '5',
	), array(
		'label' => 'Daily ads',
		'hint' => 'Required',
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'daily_repost',
		'value' => $__vars['data']['daily_repost'],
		'autosize' => 'true',
		'row' => '5',
	), array(
		'label' => 'Daily repost',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => '',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('limitations/save', $__vars['data'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);