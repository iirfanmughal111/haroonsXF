<?php
// FROM HASH: fdf3218a593a26dd2e9e7dc2b44798a8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__vars['note']['note_id']) {
		$__compilerTemp1 .= ' Edit note ' . $__templater->escape($__vars['note']['title']) . ' ';
	} else {
		$__compilerTemp1 .= ' Add new note
  ';
	}
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('
  ' . $__compilerTemp1 . '
');
	$__finalCompiled .= '

' . $__templater->form('
  <div class="block-container">
    <div class="block-body">
      ' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['note']['title'],
	), array(
		'label' => 'Title',
	)) . '
      ' . $__templater->formTextAreaRow(array(
		'name' => 'content',
		'value' => $__vars['note']['content'],
		'autosize' => 'true',
		'row' => '5',
	), array(
		'label' => 'Your note',
	)) . '
    </div>
    ' . $__templater->formSubmitRow(array(
		'submit' => 'save',
		'fa' => 'fa-save',
	), array(
	)) . '
  </div>
', array(
		'action' => $__templater->func('link', array('notes/create-insert', $__vars['note'], ), false),
		'class' => 'block',
		'ajax' => '1',
	)) . '
';
	return $__finalCompiled;
}
);