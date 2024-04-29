<?php
// FROM HASH: 47c98aee9eb93a67300f578dff1160c1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Item Fields');
	$__finalCompiled .= '

' . $__templater->includeTemplate('bh_base_custom_field_list', $__vars);
	return $__finalCompiled;
}
);