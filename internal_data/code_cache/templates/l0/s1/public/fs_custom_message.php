<?php
// FROM HASH: 2cab13468b4f01a8578a043e8732bac3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div style="color: ' . $__templater->escape($__vars['xf']['options']['fs_mts_customMsgColor']) . ';
			background-color: ' . $__templater->escape($__vars['xf']['options']['fs_mts_customMsgbackGroundColor']) . ';
			font-size: larger;
			padding: 8px;">
	' . $__templater->escape($__vars['customMessage']) . '
</div>';
	return $__finalCompiled;
}
);