<?php
// FROM HASH: 1aafe8be1d1b54a9258a28357ba3d728
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('snog_tv.less');
	$__finalCompiled .= '
<div class="node-description">
	';
	if ($__vars['node']['TVForum']['tv_genres']) {
		$__finalCompiled .= 'Genre' . ': ' . $__templater->filter($__vars['node']['TVForum']['tv_genres'], array(array('raw', array()),), true) . '<br />';
	}
	$__finalCompiled .= '
	';
	if ($__vars['node']['TVForum']['tv_director']) {
		$__finalCompiled .= 'Creator' . ': ' . $__templater->filter($__vars['node']['TVForum']['tv_director'], array(array('raw', array()),), true) . '<br />';
	}
	$__finalCompiled .= '
	';
	if ($__vars['node']['TVForum']['tv_release']) {
		$__finalCompiled .= 'First aired' . ': ' . $__templater->escape($__vars['node']['TVForum']['tv_release']);
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);