<?php
// FROM HASH: 44adf0bb1e4ffb82b5291554750b2c97
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Trader history');
	$__finalCompiled .= '

' . $__templater->func('username_link', array($__vars['user'], true, array(
	))) . '

<br /><br />

<div class="block">
	<div class="block-container">
		<div class="block-body block-row" style="padding:20px">

			';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('trader', 'view', ))) {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('trader/ratingseller', '', array('user_id' => $__vars['userId'], ), ), true) . '" rel="nofollow">' . 'Selling history' . ' (' . $__templater->escape($__vars['sellerCount']) . ')</a>
			';
	}
	$__finalCompiled .= '
			
			<br />
			<br />
			
			';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('trader', 'view', )) AND $__templater->method($__vars['xf']['visitor'], 'hasPermission', array('trader', 'rate', ))) {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('trader/rateseller', '', array('user_id' => $__vars['userId'], ), ), true) . '" rel="nofollow">' . 'Submit feedback' . '</a>
			';
	}
	$__finalCompiled .= '
			
		</div>
	</div>
</div>

<div class="block">
	<div class="block-container">
		<div class="block-body block-row" style="padding:20px">

			';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('trader', 'view', ))) {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('trader/ratingbuyer', '', array('user_id' => $__vars['userId'], ), ), true) . '" rel="nofollow">' . 'Buying history' . ' (' . $__templater->escape($__vars['buyerCount']) . ')</a>
			';
	}
	$__finalCompiled .= '

			<br />
			<br />

			';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('trader', 'view', )) AND $__templater->method($__vars['xf']['visitor'], 'hasPermission', array('trader', 'rate', ))) {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('trader/ratebuyer', '', array('user_id' => $__vars['userId'], ), ), true) . '" rel="nofollow">' . 'Submit feedback' . '</a>
			';
	}
	$__finalCompiled .= '
			
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);