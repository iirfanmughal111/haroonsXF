<?php
// FROM HASH: 7bbe1f35487091bd752d941c96089f7a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('tvthreads_interface', 'new_season', ))) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
		' . $__templater->button('
			' . 'Add season' . '
		', array(
			'href' => $__templater->func('link', array('forums/newseason', $__vars['forum'], ), false),
			'class' => 'button--cta',
			'icon' => 'write',
			'overlay' => 'true',
		), '', array(
		)) . '
	');
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);