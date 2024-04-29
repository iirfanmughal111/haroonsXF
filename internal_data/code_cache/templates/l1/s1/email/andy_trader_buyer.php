<?php
// FROM HASH: b5a9ba1c5606e6e21f610e0c1d48dd12
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . 'Trader feedback' . ' (' . 'from' . ' ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ')
</mail:subject>

<h2>' . 'Trader feedback' . '</h2>

' . 'Buyer' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('canonical:members', $__vars['buyer'], ), true) . '">' . $__templater->escape($__vars['buyer']['username']) . '</a><br />
' . 'Seller' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('canonical:members', $__vars['seller'], ), true) . '">' . $__templater->escape($__vars['seller']['username']) . '</a><br />

';
	if ($__vars['rating'] == 0) {
		$__finalCompiled .= '
	' . 'Seller rating' . $__vars['xf']['language']['label_separator'] . ' ' . 'Positive' . '<br />
';
	}
	$__finalCompiled .= '

';
	if ($__vars['rating'] == 1) {
		$__finalCompiled .= '
	' . 'Seller rating' . $__vars['xf']['language']['label_separator'] . ' ' . 'Neutral' . '<br />
';
	}
	$__finalCompiled .= '

';
	if ($__vars['rating'] == 2) {
		$__finalCompiled .= '
	' . 'Seller rating' . $__vars['xf']['language']['label_separator'] . ' ' . 'Negative' . '<br />
';
	}
	$__finalCompiled .= '

' . 'Seller comment' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['seller_comment']);
	return $__finalCompiled;
}
);