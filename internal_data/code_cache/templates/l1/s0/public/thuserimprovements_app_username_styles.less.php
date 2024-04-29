<?php
// FROM HASH: 875c32129a45cbe7eacad83dc83d1e26
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.username--deactivated {.xf-klUIDisabledUsername()}

';
	$__compilerTemp1 = $__templater->func('range', array(1, 27, ), false);
	if ($__templater->isTraversable($__compilerTemp1)) {
		foreach ($__compilerTemp1 AS $__vars['x']) {
			$__finalCompiled .= '
.username--color-' . $__templater->escape($__vars['x']) . ' {color: @xf-klUIUsernameColor' . $__templater->escape($__vars['x']) . '}
';
		}
	}
	return $__finalCompiled;
}
);