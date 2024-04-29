<?php
// FROM HASH: 207d014b2c838688922ec331db02ee28
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('tvthreads_interface', 'new_show', ))) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
		' . $__templater->button('
			' . 'Add TV show' . '
		', array(
			'href' => $__templater->func('link', array('forums/addtvforum', $__vars['category'], ), false),
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