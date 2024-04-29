<?php
// FROM HASH: 4c933bf9caf51d45f47049fc8c86dc40
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="tvnode-poster">
	';
	if ($__vars['node']['TVForum']['tv_parent_id']) {
		$__finalCompiled .= '
		<img src="' . $__templater->escape($__templater->method($__vars['node']['TVForum'], 'getSeasonPosterUrl', array())) . '" />
	';
	} else {
		$__finalCompiled .= '
		<img src="' . $__templater->escape($__templater->method($__vars['node']['TVForum'], 'getForumPosterUrl', array())) . '" />
	';
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);