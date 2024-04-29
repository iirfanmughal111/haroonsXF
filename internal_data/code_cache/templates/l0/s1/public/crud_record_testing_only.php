<?php
// FROM HASH: 335e3786cd0a5fe9fa335c9364dbcd59
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->inlineCss('
	.paymnet_btc_img{
	margin-left:3px;
	display:inline-block;
	width:25px;
	height:25px;
	}
');
	$__finalCompiled .= '

<style>
	.container {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-around;
		align-items: stretch;
	}

	.box {
		flex: 1;
		min-width: 200px; /* Set minimum width for each box */
		margin: 25px 7px;
		padding: 2px;
		background-color: #fff;
		color: #000;
		border-radius: 5px;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}

	.inCenter{
		text-align: center;
	}

	/* Media query for responsiveness */
	@media screen and (max-width: 600px) {
		.box {
			min-width: 100%; /* Make boxes full width on small screens */
		}
	}

</style>

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Upgrade Account');
	$__finalCompiled .= '
';
	$__templater->includeCss('notices.less');
	$__finalCompiled .= '

<div class="container">
	<div class="box">
		<h3 class="inCenter">VIP Companion Subscription :</h3>
		<label>Monthly Access to:</label>
		<ul>
			<li>Ideal for highly active board members.</li>
			<li>1-month subscription with each purchase.</li>
			<li>Post up to 4 times daily.</li>
			<li>Option to repost.</li>
			<li>Enhanced photo storage capacity.</li>
			<li>Price: $120/month.</li>
		</ul>
		<div class="inCenter">
			<a href="" class="blockoPayBtn button button--icon " data-toggle="modal" data-uid=07f50d8e6a44405c>
				Purchase 
				<img class="paymnet_btc_img "  src="' . $__templater->func('base_url', array('styles/FS/BitcoinIntegration/btc.png', ), true) . '">
			</a>
		</div>
	</div>

	<div class="box">
		<h3 class="inCenter">Premium Companion Subscription :</h3>
		<ul>
			<li>Great for those who plan to use the site multiple times.</li>
			<li>Post up to 2 times daily.</li>
			<li>Price: $80.</li>
		</ul>
		<div class="inCenter">
			<a href="" class="blockoPayBtn button button--icon " data-toggle="modal" data-uid=07f50d8e6a44405c>
				Purchase 
				<img class="paymnet_btc_img " src="' . $__templater->func('base_url', array('styles/FS/BitcoinIntegration/btc.png', ), true) . '">
			</a>
		</div>
	</div>

	<div class="box">
		<h3 class="inCenter">Top 20 Provider VIP Highlight :</h3>
		<ul>
			<li>Limited to 20 spots.</li>
			<li>Featured at the top of the board.</li>
			<li>30-day highlight period.</li>
			<li>Price: $200 for 30 days.</li>
		</ul>
		<div class="inCenter">
			<a href="" class="blockoPayBtn button button--icon " data-toggle="modal" data-uid=07f50d8e6a44405c>
				Purchase 
				<img class="paymnet_btc_img " src="' . $__templater->func('base_url', array('styles/FS/BitcoinIntegration/btc.png', ), true) . '">
			</a>
		</div>
	</div>

	<div class="box">
		<h3 class="inCenter">Top 10 Provider City Highlight :</h3>
		<ul>
			<li>Limited to 10 spots.</li>
			<li>Featured at the top of each city listing.</li>
			<li>30-day highlighted period.</li>
			<li>Price: $100 for 30 days.</li>
		</ul>
		<div class="inCenter">
			<a href="" class="blockoPayBtn button button--icon " data-toggle="modal" data-uid=07f50d8e6a44405c>
				Purchase 
				<img class="paymnet_btc_img " src="' . $__templater->func('base_url', array('styles/FS/BitcoinIntegration/btc.png', ), true) . '">
			</a>
		</div>
	</div>

</div>

<div class="block-container">
	<div class="block-body block-row">
		<h3>VIP Companion Subscription :</h3>
		<ul>
			<li>Ideal for highly active board members.</li>
			<li>1-month subscription with each purchase.</li>
			<li>Post up to 4 times daily.</li>
			<li>Option to repost.</li>
			<li>Enhanced photo storage capacity.</li>
			<li>Price: $120/month.</li>
		</ul>
		<div>
			<a href="" class="blockoPayBtn button button--icon " data-toggle="modal" data-uid=7d7e97f3cf4d403c>
				Purchase 
				<img class="paymnet_btc_img "  src="' . $__templater->func('base_url', array('styles/FS/BitcoinIntegration/btc.png', ), true) . '">
			</a>
		</div>
	</div>
</div>
<style>

</style>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script src="https://blockonomics.co/js/pay_widget.js"></script>
<input type="email" id="email" placeholder="Email Address"/>

<button id="pay">Pay with Bitcoin</button>
<div id="bitcoinpay"></div>

<script>
	function pay() {
		var email = document.getElementById(\'email\').value;
		Blockonomics.widget({
			msg_area: \'bitcoinpay\',
			custom_field1: \'testeer\',
			uid: \'08785fe7b68d4191\',
			email: email,
			custom_one: \'testeeye\'
		});
	}

	document.getElementById(\'pay\').onclick = function() { pay() };
</script>
<script> 
	$("#blockoPayBtnSubmit").click(function() {
		// Your code to execute when the element is clicked
		alert("Button clicked!");
	});


</script>';
	return $__finalCompiled;
}
);