<?php
// FROM HASH: f96e545a8738de14b853f85d0de123c4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->form('
  <div class="menu-row">
    ' . 'Name Contains' . $__vars['xf']['language']['label_separator'] . '
    <div class="u-inputSpacer">
      ' . $__templater->formTextBox(array(
		'name' => 'name',
		'type' => 'search',
		'value' => $__vars['conditions']['name'],
		'placeholder' => 'what u want to find in Name?',
		'dir' => 'ltr',
	)) . '
    </div>
  </div>

  <div class="menu-row">
    ' . 'Class Contains' . $__vars['xf']['language']['label_separator'] . '
    <div class="u-inputSpacer">
      ' . $__templater->formTextBox(array(
		'name' => 'rClass',
		'type' => 'search',
		'value' => $__vars['conditions']['rClass'],
		'placeholder' => 'what u want to find in Class?',
		'dir' => 'ltr',
	)) . '
    </div>
  </div>

  <div class="menu-row">
    ' . 'Roll No Contains' . $__vars['xf']['language']['label_separator'] . '
    <div class="u-inputSpacer">
      ' . $__templater->formTextBox(array(
		'name' => 'rollNo',
		'type' => 'search',
		'value' => $__vars['conditions']['rollNo'],
		'placeholder' => 'what u want to find in Roll No?',
		'dir' => 'ltr',
	)) . '
    </div>
  </div>

  <div class="menu-footer">
    <span class="menu-footer-controls">
      ' . $__templater->button('Filter', array(
		'type' => 'submit',
		'class' => 'button--primary',
	), '', array(
	)) . '
    </span>
  </div>
  ' . $__templater->formHiddenVal('search', '1', array(
	)) . '
', array(
		'action' => $__templater->func('link', array('crud', ), false),
	)) . '
';
	return $__finalCompiled;
}
);