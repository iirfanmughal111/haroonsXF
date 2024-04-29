<?php
// FROM HASH: 2bb05767122437cc4c60afa31ff5900e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<style>
	.container {
		max-width: 600px;
		margin: 0 auto;
		padding: 20px;

	}	
</style>

<div class="block">
	<div class="block-container">
		<div class="block-body">
			<div class="container">
				<center>
					<h1>Thank You for Your Purchase</h1>
					<p>We\'ve received your purchase and are currently processing it.</p>
					<p>Rest assured, you will receive an email confirmation as soon as your payment has been successfully processed.</p>
					<br/>
					' . $__templater->button('
						' . 'Back' . '
					', array(
		'href' => $__templater->func('link', array('forums/', ), false),
		'icon' => 'reply',
	), '', array(
	)) . '
				</center>
				<br/>
			</div>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);