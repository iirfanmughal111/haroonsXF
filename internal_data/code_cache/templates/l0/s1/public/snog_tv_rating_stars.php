<?php
// FROM HASH: 6f0bdb2e6507982096aec6553c59b84e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li>
	' . $__templater->callMacro('rating_macros', 'stars', array(
		'rating' => $__vars['thread']['TV']['tv_rating'],
	), $__vars) . '
</li>';
	return $__finalCompiled;
}
);