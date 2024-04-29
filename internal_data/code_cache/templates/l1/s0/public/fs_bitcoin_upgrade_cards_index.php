<?php
// FROM HASH: c733d09b1d4f7a1ec629aca92f0b80f8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Account Upgrade');
	$__finalCompiled .= '

';
	$__templater->includeCss('user_upgrad_card.less');
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => ('bionomics/upgrade.js?' . $__vars['xf']['time']),
	));
	$__finalCompiled .= '
<div class="creditsPricingCards" id="recurringCreditsCards">

	';
	if ($__vars['xf']['visitor']['user_id'] == 2) {
		$__finalCompiled .= '
		' . $__templater->callMacro('fs_bitcoin_upgrade_card_macros', 'men', array(
			'sixMonthUpgrade' => $__vars['sixMonthUpgrade'],
			'oneYearUpgrade' => $__vars['oneYearUpgrade'],
		), $__vars) . '
		';
	} else if ($__vars['xf']['visitor']['user_id'] == 1) {
		$__finalCompiled .= '
		' . $__templater->callMacro('fs_bitcoin_upgrade_card_macros', 'women', array(
			'premiumUpgrade' => $__vars['premiumUpgrade'],
			'providerCityUpgrade' => $__vars['providerCityUpgrade'],
			'vipUpgrade' => $__vars['vipUpgrade'],
			'providerVipUpgrade' => $__vars['providerVipUpgrade'],
		), $__vars) . '
	';
	}
	$__finalCompiled .= '

</div>
<div id="purchase_bitcoin"></div>';
	return $__finalCompiled;
}
);