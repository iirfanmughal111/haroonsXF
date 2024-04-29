<?php
// FROM HASH: 1fe3bef6ebd63d79b7812c675fdc00cd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.siropuChatActivityStatus
{
	&:after
	{
		content: "\\25C9";
	}
	&[data-status="active"]
	{
		color: lightgreen;
	}
	&[data-status="idle"]
	{
		color: lightgray;
	}
}';
	return $__finalCompiled;
}
);