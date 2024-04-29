<?php
// FROM HASH: f385e72423861b8d84a8387a0dce2a65
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(' ' . 'Security Questions' . ' ');
	$__finalCompiled .= '

';
	$__templater->wrapTemplate('account_wrapper', $__vars);
	$__finalCompiled .= '

';
	$__vars['customs'] = array($__vars['c1'], $__vars['c2'], );
	$__compilerTemp1 = '';
	$__vars['i'] = 0;
	if ($__templater->isTraversable($__vars['customs'])) {
		foreach ($__vars['customs'] AS $__vars['custom']) {
			$__vars['i']++;
			$__compilerTemp1 .= '
	
	';
			if ($__templater->isTraversable($__vars['questions'])) {
				foreach ($__vars['questions'] AS $__vars['question']) {
					$__compilerTemp1 .= '

					
					
	';
				}
			}
			$__compilerTemp1 .= '
	  ';
			$__compilerTemp2 = array(array(
				'value' => '0',
				'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
				'_type' => 'option',
			));
			if ($__templater->isTraversable($__vars['questions'])) {
				foreach ($__vars['questions'] AS $__vars['question']) {
					$__compilerTemp2[] = array(
						'value' => $__vars['question']['question_id'],
						'selected' => ($__vars['question']['question_id'] == $__vars['questionExist'][$__vars['i']]),
						'label' => $__templater->escape($__vars['question']['security_question']),
						'_type' => 'option',
					);
				}
			}
			$__compilerTemp1 .= $__templater->formSelectRow(array(
				'name' => 'questions[]',
				'required' => 'true',
			), $__compilerTemp2, array(
				'label' => 'Question' . ' ' . $__templater->escape($__vars['i']),
				'hint' => 'Required',
			)) . '
	
			 ' . $__templater->formRow('
				' . $__templater->formTextBoxRow(array(
				'name' => 'answers[]',
				'value' => $__vars['answersArray'][$__vars['i']],
				'placeholder' => 'Enter first answer',
				'data-i' => '0',
				'required' => 'true',
			), array(
				'rowtype' => 'fullWidth',
			)) . '
	', array(
				'rowtype' => 'input',
				'label' => 'Answer' . ' ' . $__templater->escape($__vars['i']),
				'hint' => 'Required',
			)) . '
	 
			 

	';
		}
	}
	$__finalCompiled .= $__templater->form('
  <div class="block-container">
    <div class="block-body">
      <!-- Question One -->
' . '' . '
' . $__compilerTemp1 . '

    </div>
    ' . $__templater->formSubmitRow(array(
		'submit' => '',
		'icon' => 'save',
	), array(
	)) . '
  </div>
', array(
		'action' => $__templater->func('link', array('sec-qu/save', ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);