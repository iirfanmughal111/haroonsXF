<?php
// FROM HASH: 34df7a37c16d3cda638c6b1927ec0b3f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['extraOptions'] = $__templater->preEscaped('
		' . $__templater->callMacro('base_custom_field_macros', 'common_options', array(
		'field' => $__vars['field'],
	), $__vars) . '
	');
	$__finalCompiled .= $__templater->includeTemplate('bh_base_customFields_macro', $__compilerTemp1);
	return $__finalCompiled;
}
);