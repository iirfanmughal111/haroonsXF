<?php
// FROM HASH: 7db80e2f06259b0412202824a807a251
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('snog_movies.less');
	$__finalCompiled .= '
<img class="moviePoster" src="' . $__templater->escape($__templater->method($__vars['thread']['Movie'], 'getImageUrl', array('s', ))) . '" />';
	return $__finalCompiled;
}
);