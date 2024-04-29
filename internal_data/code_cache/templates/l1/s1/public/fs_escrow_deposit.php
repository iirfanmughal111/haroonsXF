<?php
// FROM HASH: 7c56dbfd6753dabfb51ab51f051926e3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(' ' . 'Deposit amount' . ' ');
	$__finalCompiled .= '

';
	$__templater->wrapTemplate('account_wrapper', $__vars);
	$__finalCompiled .= '

' . $__templater->form('
  <div class="block-container">
    <div class="block-body">
      ' . $__templater->formNumberBoxRow(array(
		'name' => 'deposit_amount',
		'min' => '0',
	), array(
		'explain' => 'Current Balance:' . ' ' . '$' . $__templater->escape($__vars['xf']['visitor']['deposit_amount']),
		'label' => 'Amount',
	)) . '
    </div>
    ' . $__templater->formSubmitRow(array(
		'submit' => '',
		'icon' => 'save',
	), array(
	)) . '
  </div>
', array(
		'action' => $__templater->func('link', array('escrow/deposit-save', ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);