<?php
// FROM HASH: b29f8e92216cdc22dbba624b8063dd8f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Create personal album');
	$__finalCompiled .= '

' . $__templater->callMacro('xfmg_media_add_macros', 'add_form', array(
		'album' => $__vars['album'],
		'canUpload' => $__templater->method($__vars['album'], 'canUploadMedia', array()),
		'canEmbed' => $__templater->method($__vars['album'], 'canEmbedMedia', array()),
		'attachmentData' => $__vars['attachmentData'],
		'allowCreateAlbum' => true,
	), $__vars);
	return $__finalCompiled;
}
);