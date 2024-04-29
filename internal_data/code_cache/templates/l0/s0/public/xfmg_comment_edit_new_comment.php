<?php
// FROM HASH: c1ea23e7aa7e865e4f019b54954aefa6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xfmg_comment_macros', 'comment', array(
		'comment' => $__vars['comment'],
		'content' => $__vars['content'],
		'linkPrefix' => ((('media/' . $__vars['content']['content_type']) == 'xfmg_media') ? 'media' : ('album' . '-comments')),
	), $__vars) . '
';
	return $__finalCompiled;
}
);