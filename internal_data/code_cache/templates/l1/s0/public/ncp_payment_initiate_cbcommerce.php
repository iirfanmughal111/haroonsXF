<?php
// FROM HASH: 8be0c5aebab06ee479b7f4d5fe295457
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('payment_initiate.less');
	$__finalCompiled .= '
';
	$__templater->includeJs(array(
		'src' => 'xf/payment.js',
		'min' => '1',
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Enter payment details');
	$__finalCompiled .= '

<div class="blocks">
	' . $__templater->form('
		<div class="block-container">
			<div class="block-body">
				' . $__templater->formInfoRow('
					<div class="block-rowMessage block-rowMessage--error block-rowMessage--iconic u-hidden" id="card-errors"></div>
				', array(
		'id' => 'card-errors-container',
		'rowclass' => 'u-hidden',
	)) . '

				<hr class="formRowSep" />
				
				<iframe src="' . $__templater->escape($__vars['paymentUrl']) . '"></iframe>

				' . $__templater->formRow('
					' . $__templater->button('
						' . 'Pay ' . $__templater->filter($__vars['purchase']['cost'], array(array('currency', array($__vars['purchase']['currency'], )),), true) . '' . '
					', array(
		'type' => 'submit',
		'icon' => 'payment',
	), '', array(
	)) . '
				', array(
		'label' => '',
		'rowtype' => 'button',
	)) . '
			</div>
		</div>
	', array(
		'action' => $__templater->func('link', array('purchase/process', null, array('request_key' => $__vars['purchaseRequest']['request_key'], ), ), false),
		'class' => 'block block--paymentInitiate',
	)) . '
</div>';
	return $__finalCompiled;
}
);