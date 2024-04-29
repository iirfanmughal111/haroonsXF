<?php
// FROM HASH: 80460a6f1397756419d3c240fe7c2cd2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__vars['message']['message_id']) {
		$__compilerTemp1 .= ' ' . 'Edit Message' . ' ';
	} else {
		$__compilerTemp1 .= ' ' . 'Add Message' . ' ';
	}
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('
  ' . $__compilerTemp1 . '
');
	$__finalCompiled .= '

';
	$__compilerTemp2 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	$__compilerTemp3 = $__templater->method($__vars['nodeTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp3)) {
		foreach ($__compilerTemp3 AS $__vars['treeEntry']) {
			$__compilerTemp2[] = array(
				'value' => $__vars['treeEntry']['record']['node_id'],
				'disabled' => ($__vars['treeEntry']['record']['node_type_id'] != 'Forum'),
				'selected' => ($__vars['nodeId'] == $__vars['treeEntry']['record']['node_id']),
				'label' => $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['treeEntry']['depth'], ), false), array(array('raw', array()),), true) . '
            ' . $__templater->escape($__vars['treeEntry']['record']['title']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp4 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['userGroups'])) {
		foreach ($__vars['userGroups'] AS $__vars['userGroup']) {
			$__compilerTemp4[] = array(
				'value' => $__vars['userGroup']['user_group_id'],
				'selected' => ($__vars['userGroupId'] == $__vars['userGroup']['user_group_id']),
				'label' => $__templater->escape($__vars['userGroup']['title']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp5 = '';
	if (!$__templater->test($__vars['prefixesGrouped'], 'empty', array())) {
		$__compilerTemp5 .= '
        ';
		$__compilerTemp6 = array(array(
			'value' => '0',
			'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
			'_type' => 'option',
		));
		if ($__templater->isTraversable($__vars['prefixGroups'])) {
			foreach ($__vars['prefixGroups'] AS $__vars['prefixGroupId'] => $__vars['prefixGroup']) {
				if ($__templater->isTraversable($__vars['prefixesGrouped'][$__vars['prefixGroupId']])) {
					foreach ($__vars['prefixesGrouped'][$__vars['prefixGroupId']] AS $__vars['prefix_id'] => $__vars['prefix']) {
						$__compilerTemp6[] = array(
							'value' => $__vars['prefix_id'],
							'selected' => ($__vars['prefixId'] == $__vars['prefix_id']),
							'label' => $__templater->escape($__vars['prefix']['title']),
							'_type' => 'option',
						);
					}
				}
			}
		}
		$__compilerTemp5 .= $__templater->formSelectRow(array(
			'name' => 'prefix_id',
			'value' => $__vars['nodeIds'],
			'required' => 'required',
		), $__compilerTemp6, array(
			'label' => 'Prefixes',
			'hint' => 'Required',
		)) . '
      ';
	}
	$__vars['arr'] = 0;
	$__compilerTemp7 = '';
	$__vars['i'] = 0;
	if ($__templater->isTraversable($__vars['data'])) {
		foreach ($__vars['data'] AS $__vars['key'] => $__vars['value']) {
			$__vars['i']++;
			$__compilerTemp7 .= '
            <div class="inputGroup">
              ' . $__templater->formTextBox(array(
				'name' => 'words[]',
				'value' => $__vars['value']['word'],
				'placeholder' => 'Enter Word here...!',
				'size' => '24',
				'maxlength' => '25',
				'data-i' => '0',
				'dir' => 'ltr',
			)) . '

              <span class="inputGroup-splitter"></span>

              ' . $__templater->formTextBox(array(
				'name' => 'messages[]',
				'value' => $__vars['value']['message'],
				'placeholder' => 'Enter Message here...!',
				'size' => '24',
				'data-i' => '0',
			)) . '

              ' . $__templater->formTextBox(array(
				'name' => 'from_users[]',
				'value' => $__templater->method($__vars['value'], 'getMatchUserids', array()),
				'ac' => 'multiple',
				'placeholder' => 'Enter Existing User...!',
				'size' => '24',
				'data-i' => '0',
			)) . '
              ' . $__templater->button('', array(
				'href' => $__templater->func('link', array('forumAutoReply/edit-single', $__vars['value'], ), false),
				'overlay' => 'true',
				'style' => '
                  background-color: #fff;
                  border: none;
                  padding: 0px;
                  color: #47a7eb;
                ',
				'fa' => 'fa-edit',
			), '', array(
			)) . '
              ' . $__templater->button('', array(
				'href' => $__templater->func('link', array('forumAutoReply/delete', $__vars['value'], ), false),
				'overlay' => 'true',
				'style' => '
                  background-color: #fff;
                  border: none;
                  padding: 0px;
                  color: #47a7eb;
                ',
				'fa' => 'fa-trash',
			), '', array(
			)) . '
            </div>
            ';
			$__vars['arr'] = $__vars['i'];
			$__compilerTemp7 .= '
          ';
		}
	}
	$__compilerTemp8 = '';
	if (!$__templater->test($__vars['prefixesGrouped'], 'empty', array())) {
		$__compilerTemp8 .= '
        ';
		$__compilerTemp9 = array(array(
			'value' => '0',
			'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
			'_type' => 'option',
		));
		if ($__templater->isTraversable($__vars['prefixGroups'])) {
			foreach ($__vars['prefixGroups'] AS $__vars['prefixGroupId'] => $__vars['prefixGroup']) {
				if ($__templater->isTraversable($__vars['prefixesGrouped'][$__vars['prefixGroupId']])) {
					foreach ($__vars['prefixesGrouped'][$__vars['prefixGroupId']] AS $__vars['prefix_id'] => $__vars['prefix']) {
						$__compilerTemp9[] = array(
							'value' => $__vars['prefix_id'],
							'selected' => ($__vars['noDataMatch']['no_match_prefix_id'] == $__vars['prefix_id']),
							'label' => $__templater->escape($__vars['prefix']['title']),
							'_type' => 'option',
						);
					}
				}
			}
		}
		$__compilerTemp8 .= $__templater->formSelectRow(array(
			'name' => 'no_match_prefix_id',
			'value' => $__vars['nodeIds'],
			'required' => 'required',
		), $__compilerTemp9, array(
			'label' => 'No Word Match (Prefix)',
			'hint' => 'Required',
		)) . '
      ';
	}
	$__finalCompiled .= $__templater->form('
  <div class="block-container">
    <div class="block-body">
      <!-- Nodes list -->

      ' . $__templater->formSelectRow(array(
		'name' => 'node_id',
		'required' => 'required',
	), $__compilerTemp2, array(
		'label' => 'Forum',
		'hint' => 'Required',
	)) . '

      <!-- User Group List -->

      ' . $__templater->formSelectRow(array(
		'name' => 'user_group_id',
		'value' => $__vars['nodeIds'],
		'required' => 'required',
	), $__compilerTemp4, array(
		'label' => 'User group',
		'hint' => 'Required',
	)) . '

      <!-- Prefixes List -->

      ' . $__compilerTemp5 . '

      <!-- Words,Message,User inputs -->

      ' . $__templater->formRow('
        <div
          class="inputGroup-container"
          data-xf-init="list-sorter"
          data-drag-handle=".dragHandle"
        >
          ' . '' . '

          ' . $__compilerTemp7 . '
          <div
            class="inputGroup is-undraggable js-blockDragafter"
            data-xf-init="field-adder"
            data-remove-class="is-undraggable js-blockDragafter"
          >
            ' . $__templater->formTextBoxRow(array(
		'name' => 'words[]',
		'placeholder' => 'Enter Word here...!',
		'data-i' => '0',
		'dir' => 'ltr',
	), array(
		'rowtype' => 'fullWidth',
	)) . '

            ' . $__templater->formTextBoxRow(array(
		'name' => 'messages[]',
		'placeholder' => 'Enter Message here...!',
		'data-i' => '0',
	), array(
		'rowtype' => 'fullWidth',
	)) . '

            ' . $__templater->formTextBox(array(
		'name' => 'from_users[]',
		'ac' => 'multiple',
		'placeholder' => 'Enter Existing User...!',
		'size' => '24',
		'data-i' => '0',
		'style' => 'width: 530px; height: 36px; margin-top: 16px',
	)) . '
          </div>
        </div>
      ', array(
		'rowtype' => 'input',
		'label' => 'Word-Message',
		'hint' => 'Required',
	)) . '

      <!-- No Match Prefixes List -->

      ' . $__compilerTemp8 . '

      <!-- No Match Message,User inputs -->

      ' . $__templater->formRow('
        
          <div class="inputGroup">
            ' . $__templater->formTextBoxRow(array(
		'name' => 'no_match_messages',
		'value' => $__vars['noDataMatch']['no_match_message'],
		'placeholder' => 'Enter Message Here...!',
		'data-i' => '0',
	), array(
		'rowtype' => 'fullWidth',
	)) . '

            ' . $__templater->formTextBox(array(
		'name' => 'no_match_users',
		'ac' => 'multiple',
		'value' => ($__vars['noDataMatch'] ? $__templater->method($__vars['noDataMatch'], 'getNoMatchUserIds', array()) : ''),
		'placeholder' => 'Enter Existing User...!',
		'data-i' => '0',
		'style' => 'width: 530px; height: 36px; margin-top: 15px',
	)) . '
          </div>
     
      ', array(
		'rowtype' => 'input',
		'label' => 'No Word Match (Message)',
		'hint' => 'Required',
	)) . '
    </div>
    ' . $__templater->formSubmitRow(array(
		'submit' => '',
		'icon' => 'save',
	), array(
	)) . '
  </div>
', array(
		'action' => $__templater->func('link', array('forumAutoReply/save', $__vars['message'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);