<?php
// FROM HASH: 31d6ac6d0abd9fa45bc3623953381ef8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(' ');
	$__finalCompiled .= '

' . $__templater->form('
  <div class="block-container">
    <div class="block-body">
      ' . $__templater->formRow('
        ' . $__templater->formTextBoxRow(array(
		'name' => 'user_name',
		'placeholder' => 'Enter User Name',
		'data-i' => '0',
	), array(
		'rowtype' => 'fullWidth',
	)) . '
      ', array(
		'rowtype' => 'input',
		'label' => 'User Name',
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
		'action' => $__templater->func('link', array('sec-qu/verifyuser', ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	)) . '
';
	return $__finalCompiled;
}
);