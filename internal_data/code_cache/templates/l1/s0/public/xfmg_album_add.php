<?php
// FROM HASH: a246eec17b836d2bd28259cb62396d12
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add media' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['album']['title']));
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['album'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->callMacro('xfmg_media_add_macros', 'add_form', array(
		'album' => $__vars['album'],
		'canUpload' => $__templater->method($__vars['album'], 'canUploadMedia', array()),
		'canEmbed' => $__templater->method($__vars['album'], 'canEmbedMedia', array()),
		'attachmentData' => $__vars['attachmentData'],
	), $__vars);
	return $__finalCompiled;
}
);