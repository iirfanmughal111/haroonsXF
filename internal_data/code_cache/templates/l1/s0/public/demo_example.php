<?php
// FROM HASH: bfdad01a05e08e4f1f6d8895c0986045
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->escape($__vars['hello']) . ' ' . $__templater->escape($__vars['world']);
	return $__finalCompiled;
}
);