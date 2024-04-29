<?php
// FROM HASH: 227059723a3bd7860af807c670a859c0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . 'Trader feedback' . ' (' . 'from' . ' ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ')
</mail:subject>

<h2>' . 'Trader feedback' . '</h2>

' . 'Seller' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('canonical:members', $__vars['seller'], ), true) . '">' . $__templater->escape($__vars['seller']['username']) . '</a><br />
' . 'Buyer' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('canonical:members', $__vars['buyer'], ), true) . '">' . $__templater->escape($__vars['buyer']['username']) . '</a><br />

';
	if ($__vars['rating'] == 0) {
		$__finalCompiled .= '
	' . 'Buyer rating' . $__vars['xf']['language']['label_separator'] . ' ' . 'Positive' . '<br />
';
	}
	$__finalCompiled .= '

';
	if ($__vars['rating'] == 1) {
		$__finalCompiled .= '
	' . 'Buyer rating' . $__vars['xf']['language']['label_separator'] . ' ' . 'Neutral' . '<br />
';
	}
	$__finalCompiled .= '

';
	if ($__vars['rating'] == 2) {
		$__finalCompiled .= '
	' . 'Buyer rating' . $__vars['xf']['language']['label_separator'] . ' ' . 'Negative' . '<br />
';
	}
	$__finalCompiled .= '

' . 'Buyer comment' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['buyer_comment']);
	return $__finalCompiled;
}
);