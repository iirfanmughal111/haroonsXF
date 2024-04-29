<?php
// FROM HASH: d52f62c6cb8a260a1fcc4ac7c3c01673
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(' ' . 'Verify Questions' . ' ');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['questions'])) {
		foreach ($__vars['questions'] AS $__vars['question']) {
			$__compilerTemp1 .= '

		' . $__templater->formRow($__templater->escape($__vars['question']['userQuestion']['security_question']), array(
				'label' => 'Question',
				'hint' => 'Required',
			)) . '
					
			<input type="hidden" name="questions[]" value="' . $__templater->escape($__vars['question']['question_id']) . '"/>
			 ' . $__templater->formRow('
				' . $__templater->formTextBoxRow(array(
				'name' => 'answers[]',
				'placeholder' => 'Enter first answer',
				'data-i' => '0',
				'required' => 'true',
			), array(
				'rowtype' => 'fullWidth',
			)) . '
	', array(
				'rowtype' => 'input',
				'label' => 'Answer',
				'hint' => 'Required',
			)) . '
		
	';
		}
	}
	$__finalCompiled .= $__templater->form('
   <div class="block-container">
    <div class="block-body">
      <!-- Question One -->
	
	' . $__compilerTemp1 . '	
	 
			 

	
    </div>
	   <input type="hidden" name="user_id" value=' . $__templater->escape($__vars['user']) . ' />
    ' . $__templater->formSubmitRow(array(
		'submit' => '',
		'icon' => 'save',
	), array(
	)) . '
  </div>
', array(
		'action' => $__templater->func('link', array('sec-qu/compare', ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);