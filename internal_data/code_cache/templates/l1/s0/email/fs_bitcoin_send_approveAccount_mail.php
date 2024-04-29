<?php
// FROM HASH: b075b2f2819b59885656ddbce520349c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . 'SouthWestBoard' . '
</mail:subject>

' . '<p>Hey, <strong>' . $__templater->escape($__vars['user']['username']) . '</strong>. Your account approved successfully</p>';
	return $__finalCompiled;
}
);