<?php
// FROM HASH: 608129f6d8affecde5d28f26b2d179fa
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__vars['question']['question_id']) {
		$__compilerTemp1 .= ' ' . 'Edit question' . ' :
  ';
		$__templater->breadcrumb($__templater->preEscaped('Edit question'), '#', array(
		));
		$__compilerTemp1 .= ' ';
	} else {
		$__compilerTemp1 .= ' ' . 'Add Question' . ' ';
		$__templater->breadcrumb($__templater->preEscaped('Add Question'), '#', array(
		));
		$__compilerTemp1 .= ' ';
	}
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('
  ' . $__compilerTemp1 . '
');
	$__finalCompiled .= '

' . $__templater->form('
  <div class="block-container">
    <div class="block-body">
      ' . $__templater->formTextBoxRow(array(
		'name' => 'security_question',
		'value' => $__vars['question']['security_question'],
		'autosize' => 'true',
		'row' => '5',
	), array(
		'label' => 'Question',
	)) . '
    </div>

    ' . $__templater->formSubmitRow(array(
		'submit' => 'save',
		'fa' => 'fa-save',
	), array(
	)) . '
  </div>
', array(
		'action' => $__templater->func('link', array('securityQuestion/save', $__vars['question'], ), false),
		'class' => 'block',
		'ajax' => '1',
	)) . '
';
	return $__finalCompiled;
}
);