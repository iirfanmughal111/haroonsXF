<?php
// FROM HASH: 0f49891ea334d0fdab2e9ba145916e78
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Gallery');
	$__finalCompiled .= '

' . $__templater->callMacro('section_nav_macros', 'section_nav', array(
		'section' => 'xfmg',
	), $__vars);
	return $__finalCompiled;
}
);