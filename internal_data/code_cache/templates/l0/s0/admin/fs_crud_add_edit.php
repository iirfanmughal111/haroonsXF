<?php
// FROM HASH: 093de94440c0bd20468827a643f1351a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__vars['data']['id']) {
		$__compilerTemp1 .= ' ' . 'Edit Record' . ' : ' . $__templater->escape($__vars['data']['name']) . ' ';
		$__templater->breadcrumb($__templater->preEscaped('Edit Record'), '#', array(
		));
		$__compilerTemp1 .= ' ';
	} else {
		$__compilerTemp1 .= 'Add New Record' . '
		';
		$__templater->breadcrumb($__templater->preEscaped('Add Record'), '#', array(
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
		'name' => 'name',
		'value' => $__vars['data']['name'],
		'autosize' => 'true',
		'row' => '5',
	), array(
		'label' => 'Name',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'class',
		'value' => $__vars['data']['class'],
		'autosize' => 'true',
		'row' => '5',
	), array(
		'label' => 'Class',
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'rollNo',
		'value' => $__vars['data']['rollNo'],
		'autosize' => 'true',
		'row' => '5',
	), array(
		'label' => 'Roll No',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => '',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('crud/save', $__vars['data'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);