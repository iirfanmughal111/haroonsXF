<?php
// FROM HASH: 8c9b12743abbc2c3b32970fff79ef868
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(' ' . 'Verify By' . ' ');
	$__finalCompiled .= '

' . $__templater->form('
  <div class="block-container">
    <div class="block-body">
      ' . $__templater->formSelectRow(array(
		'name' => 'select_option',
		'required' => 'required',
	), array(array(
		'value' => '1',
		'label' => 'Email',
		'_type' => 'option',
	),
	array(
		'value' => '2',
		'label' => 'Security Questions',
		'_type' => 'option',
	)), array(
		'label' => 'Select Method',
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
		'action' => $__templater->func('link', array('sec-qu/select', ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	)) . '
';
	return $__finalCompiled;
}
);