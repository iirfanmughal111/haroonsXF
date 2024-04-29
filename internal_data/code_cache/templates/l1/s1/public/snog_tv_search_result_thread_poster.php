<?php
// FROM HASH: 4a24976ce4168f61eef8b9970d78f20e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('snog_tv.less');
	$__finalCompiled .= '
<img class="tvPoster" src="' . $__templater->escape($__templater->method($__vars['thread']['TV'], 'getImageUrl', array('s', ))) . '" />';
	return $__finalCompiled;
}
);