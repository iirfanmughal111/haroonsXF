<?php
// FROM HASH: bd84b616274e050d8ec21e835802ed8d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div id="make_attach_' . $__templater->escape($__vars['content']['post_id']) . '">

	' . $__templater->callMacro('message_macros', 'attachments', array(
		'attachments' => $__vars['content']['Attachments'],
		'message' => $__vars['content'],
		'canView' => $__templater->method($__vars['content']['Thread'], 'canViewAttachments', array()),
	), $__vars) . '
	
</div>';
	return $__finalCompiled;
}
);