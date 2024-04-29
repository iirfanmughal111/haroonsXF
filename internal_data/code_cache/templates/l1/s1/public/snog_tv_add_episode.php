<?php
// FROM HASH: 8c1a15a4eb3d67cb1b88bd8b38943c22
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('tvthreads_interface', 'new_episode', ))) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
		' . $__templater->button('
			' . 'Add episode' . '
		', array(
			'href' => $__templater->func('link', array('forums/newepisode', $__vars['forum'], ), false),
			'class' => 'button--cta',
			'icon' => 'write',
		), '', array(
		)) . '
	');
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);