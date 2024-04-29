<?php
// FROM HASH: 14e044123fb774fea8f0d8c8a5e4036d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit selling history');
	$__finalCompiled .= '

';
	$__templater->includeCss('andy_trader.less');
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('trader', 'admin', ))) {
		$__finalCompiled .= '
<a href="' . $__templater->func('link', array('trader/deleteseller', '', array('trader_id' => $__vars['traderId'], ), ), true) . '">' . 'Delete' . '</a>
<br /><br />
';
	}
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">

			' . $__templater->formTextBoxRow(array(
		'name' => 'trader_id',
		'readonly' => 'readonly',
		'value' => $__vars['results']['trader_id'],
		'class' => 'trader-muted',
	), array(
		'label' => 'Trader ID',
	)) . '
			
			' . $__templater->formTextBoxRow(array(
		'name' => 'username',
		'readonly' => 'readonly',
		'value' => $__vars['results']['UserSeller']['username'],
		'class' => 'trader-muted',
	), array(
		'label' => 'Username',
	)) . '
			
			' . $__templater->formRadioRow(array(
		'name' => 'rating',
	), array(array(
		'value' => '0',
		'checked' => $__vars['checked1'],
		'label' => 'Positive',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'checked' => $__vars['checked2'],
		'label' => 'Neutral',
		'_type' => 'option',
	),
	array(
		'value' => '2',
		'checked' => $__vars['checked3'],
		'label' => 'Negative',
		'_type' => 'option',
	)), array(
		'label' => 'Rating',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'buyer_comment',
		'value' => $__vars['results']['buyer_comment'],
		'rows' => '5',
		'autosize' => 'true',
	), array(
		'label' => 'Buyer comment',
		'explain' => 'As a buyer please describe your experience with this seller. (200 character limit)',
	)) . '
		</div>
	</div>

	' . $__templater->formSubmitRow(array(
		'submit' => 'Submit',
	), array(
	)) . '

', array(
		'action' => $__templater->func('link', array('trader/updateseller', '', array('trader_id' => $__vars['traderId'], ), ), false),
		'ajax' => 'false',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);