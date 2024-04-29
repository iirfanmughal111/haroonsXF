<?php
// FROM HASH: 07db2ca3e617872822e725b8ab716b0a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formTextBoxRow(array(
		'name' => 'options[api_key]',
		'value' => $__vars['profile']['options']['api_key'],
	), array(
		'label' => 'API key',
		'explain' => 'Enter your API key for Coinbase Commerce. You can find this <a href="https://commerce.coinbase.com/dashboard/settings">here</a>. Additionally, you may also need to whitelist your domain on that page (your board URL is: ' . $__templater->escape($__vars['xf']['options']['boardUrl']) . ').',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'options[webhook_secret]',
		'value' => $__vars['profile']['options']['webhook_secret'],
	), array(
		'label' => 'Webhook secret',
		'explain' => 'The key for your webhook secret. Found on the <a href="https://commerce.coinbase.com/dashboard/settings">Coinbase Commerce settings page</a>. You will also need to add your callback URL in the same location. Click "Add an endpoint" and add: ' . $__templater->escape($__vars['xf']['options']['boardUrl']) . '/' . 'payment_callback.php?_xfProvider=ncp_coinbase_commerce' . '',
	));
	return $__finalCompiled;
}
);