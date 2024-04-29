<?php
// FROM HASH: 37c9f14086d02089bf978fc5f69afcc6
return array(
'macros' => array('women' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'premiumUpgrade' => '!',
		'providerCityUpgrade' => '!',
		'vipUpgrade' => '!',
		'providerVipUpgrade' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
	<!-- Box # 1 -->

	<div class="creditsPricingCard">
		<div class="creditsPricingCardTop box3-top">
			' . '<p>' . $__templater->escape($__vars['vipUpgrade']['title']) . '</p>
<h2>' . $__templater->escape($__vars['vipUpgrade']['cost_amount']) . '</h2>
<p>/ ' . $__templater->escape($__vars['vipUpgrade']['length_unit']) . '</p>' . '

		</div>
		' . '<div class="card_descriptions" style="">
	<div class="user-upgrades__block  box3-upgrades__block">
		<ul>
			<li>Option to repost.</li>
			<li>Post as many times per day.</li>
			<li>Enhanced message storage.</li>
			<li>Enhanced photo storage capacity.</li>
			<li>Ideal for highly active board members.</li>
			<li>1-month subscription with each purchase.</li>
		</ul>
	</div>
</div><br/>' . '
		';
	if ($__vars['vipUpgrade'] AND (!$__templater->method($__vars['vipUpgrade'], 'getUserUpgradeExit', array()))) {
		$__finalCompiled .= '
			<a href=""  data-validation-url="' . $__templater->func('link', array('account-upgrade/purchase', ), true) . '" data-xf-click="show-upgrade-box" data-option-value="fs_bitcoin_vip_companion" class=" button box3-btn"  >
				' . 'Upgrade' . ' 
				<img class="paymnet_btc_img" src="' . $__templater->func('base_url', array('styles/FS/BitcoinIntegration/btc.png', ), true) . '"></a>
			';
	} else {
		$__finalCompiled .= '
			' . 'Activated' . '
		';
	}
	$__finalCompiled .= '

	</div>
	
	<!-- Box # 2 -->

	<div class="creditsPricingCard">
		<div class="creditsPricingCardTop box1-top">
			' . '<p>' . $__templater->escape($__vars['premiumUpgrade']['title']) . '</p>
<h2>' . $__templater->escape($__vars['premiumUpgrade']['cost_amount']) . '</h2>
<p>/ ' . $__templater->escape($__vars['premiumUpgrade']['length_unit']) . '</p>' . '
		</div>
		' . '<div class="card_descriptions" style="">
	<div class="user-upgrades__block box1-upgrades__block">
		<ul>
			<li>Post up to 2 times daily.</li>
			<li>Limited message storage.</li>
			<li>Limited photo storage capacity.</li>
			<li>Great for those who plan to use the site multiple times weekly.</li>
		</ul>
	</div>
</div>
<br/>
<br/>
<br/><br/><br/>' . '

		';
	if ($__vars['premiumUpgrade'] AND (!$__templater->method($__vars['premiumUpgrade'], 'getUserUpgradeExit', array()))) {
		$__finalCompiled .= '
			<a href=""  data-validation-url="' . $__templater->func('link', array('account-upgrade/purchase', ), true) . '" data-xf-click="show-upgrade-box" data-option-value="fs_bitcoin_premium_companion" class=" button box1-btn"  >
				' . 'Upgrade' . '
				<img class="paymnet_btc_img" src="' . $__templater->func('base_url', array('styles/FS/BitcoinIntegration/btc.png', ), true) . '">
			</a>
			';
	} else {
		$__finalCompiled .= '
			' . 'Activated' . '
		';
	}
	$__finalCompiled .= '

	</div>
	
	<!-- Box # 3 -->

	<div class="creditsPricingCard">
		<div class="creditsPricingCardTop box4-top">
			' . '<p>' . $__templater->escape($__vars['providerVipUpgrade']['title']) . '</p>
<h2>' . $__templater->escape($__vars['providerVipUpgrade']['cost_amount']) . '</h2>
<p>/ ' . $__templater->escape($__vars['providerVipUpgrade']['length_unit']) . '</p>' . '


		</div>
		' . '<div class="card_descriptions" style="">
	<div class="user-upgrades__block  box4-upgrades__block">
		<ul>
			<li>Limited to 20 spots.</li>
			<li>30-day highlight period.</li>
			<li>Featured at the top of the board.</li>
		</ul>
	</div>
</div>
<br/>
<br/>
<br/>
<br/><br/><br/><br/><br/><br/>' . ' 
		';
	if ($__vars['providerVipUpgrade'] AND (!$__templater->method($__vars['providerVipUpgrade'], 'getUserUpgradeExit', array()))) {
		$__finalCompiled .= '
			<a href=""  data-validation-url="' . $__templater->func('link', array('account-upgrade/purchase', ), true) . '" data-xf-click="show-upgrade-box" data-option-value="fs_bitcoin_provider_vip" class=" button box4-btn"  >
				' . 'Upgrade' . ' 
				<img class="paymnet_btc_img" src="' . $__templater->func('base_url', array('styles/FS/BitcoinIntegration/btc.png', ), true) . '">
			</a>
			';
	} else {
		$__finalCompiled .= '
			' . 'Activated' . '
		';
	}
	$__finalCompiled .= '
	</div>
	
	<!-- Box # 4 -->

	<div class="creditsPricingCard">
		<div class="creditsPricingCardTop box2-top">
			' . '<p>' . $__templater->escape($__vars['providerCityUpgrade']['title']) . '</p>
<h2>' . $__templater->escape($__vars['providerCityUpgrade']['cost_amount']) . '</h2>
<p>/ ' . $__templater->escape($__vars['providerCityUpgrade']['length_unit']) . '</p>' . '


		</div>
		' . '<div class="card_descriptions" style="">
	<div class="user-upgrades__block  box2-upgrades__block">
		<ul>
			<li>Limited to 10 spots.</li>
			<li>30-day highlighted period.</li>
			<li>Featured at the top of each city listing.</li>
		</ul>
	</div>
</div>
<br/>
<br/>
<br/>
<br/><br/><br/><br/><br/><br/>' . '
		';
	if ($__vars['providerCityUpgrade'] AND (!$__templater->method($__vars['providerCityUpgrade'], 'getUserUpgradeExit', array()))) {
		$__finalCompiled .= '
			<a href=""  data-validation-url="' . $__templater->func('link', array('account-upgrade/purchase', ), true) . '" data-xf-click="show-upgrade-box" data-option-value="fs_bitcoin_provider_city" class=" button box2-btn"  >
				' . 'Upgrade' . ' 
				<img class="paymnet_btc_img" src="' . $__templater->func('base_url', array('styles/FS/BitcoinIntegration/btc.png', ), true) . '">
			</a>
			';
	} else {
		$__finalCompiled .= '
			' . 'Activated' . '
		';
	}
	$__finalCompiled .= '
	</div>	

';
	return $__finalCompiled;
}
),
'men' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'sixMonthUpgrade' => '!',
		'oneYearUpgrade' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<div class="creditsPricingCard">
		<div class="creditsPricingCardTop box1-top">
			' . '<p><b>' . $__templater->escape($__vars['sixMonthUpgrade']['title']) . '</b></p>
<h2>' . $__templater->escape($__vars['sixMonthUpgrade']['cost_amount']) . '</h2>
<p>' . $__templater->escape($__vars['sixMonthUpgrade']['length_amount']) . ' / ' . $__templater->escape($__vars['sixMonthUpgrade']['length_unit']) . '</p>' . '
		</div>
		' . '<div class="card_descriptions" style="">
	<div class="user-upgrades__block box1-upgrades__block">
		<ul>
			<li>Post as many times per day.</li>
			<li>Unlimited message storage.</li>
			<li>Unlimited photo storage capacity.</li>
		</ul>
	</div>
</div>' . '

		';
	if ($__vars['sixMonthUpgrade'] AND (!$__templater->method($__vars['sixMonthUpgrade'], 'getUserUpgradeExit', array()))) {
		$__finalCompiled .= '
			<a href=""  data-validation-url="' . $__templater->func('link', array('account-upgrade/purchase', ), true) . '" data-xf-click="show-upgrade-box" data-option-value="fs_bitcoin_six_month" class=" button box1-btn"  >
				' . 'Upgrade' . '
				<img class="paymnet_btc_img" src="' . $__templater->func('base_url', array('styles/FS/BitcoinIntegration/btc.png', ), true) . '">
			</a>
			';
	} else {
		$__finalCompiled .= '
			' . 'Activated' . '
		';
	}
	$__finalCompiled .= '

	</div>

	<div class="creditsPricingCard">
		<div class="creditsPricingCardTop box2-top">
			' . '<p><b>' . $__templater->escape($__vars['oneYearUpgrade']['title']) . '</b></p>
<h2>' . $__templater->escape($__vars['oneYearUpgrade']['cost_amount']) . '</h2>
<p>' . $__templater->escape($__vars['oneYearUpgrade']['length_amount']) . ' / ' . $__templater->escape($__vars['oneYearUpgrade']['length_unit']) . '</p>' . '
		</div>
		' . '<div class="card_descriptions" style="">
	<div class="user-upgrades__block  box2-upgrades__block">
		<ul>
			<li>Post as many times per day.</li>
			<li>Unlimited message storage.</li>
			<li>Unlimited photo storage capacity.</li>
		</ul>
	</div>
</div>' . '
		';
	if ($__vars['oneYearUpgrade'] AND (!$__templater->method($__vars['oneYearUpgrade'], 'getUserUpgradeExit', array()))) {
		$__finalCompiled .= '
			<a href=""  data-validation-url="' . $__templater->func('link', array('account-upgrade/purchase', ), true) . '" data-xf-click="show-upgrade-box" data-option-value="fs_bitcoin_one_year" class=" button box2-btn"  >
				' . 'Upgrade' . ' 
				<img class="paymnet_btc_img" src="' . $__templater->func('base_url', array('styles/FS/BitcoinIntegration/btc.png', ), true) . '">
			</a>
			';
	} else {
		$__finalCompiled .= '
			' . 'Activated' . '
		';
	}
	$__finalCompiled .= '
	</div>

';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<!-- women -->

' . '


<!-- men -->

';
	return $__finalCompiled;
}
);