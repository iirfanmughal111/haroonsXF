<?php
// FROM HASH: d5466ab1de4c9539d63eacbbc76720ff
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('snog_tv_x_videos');
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

<div class="block">
	' . $__templater->callMacro('snog_tv_videos_macros', 'video_list', array(
		'tv' => $__vars['tv'],
		'videos' => $__vars['videos'],
		'page' => $__vars['page'],
		'hasMore' => $__vars['hasMore'],
	), $__vars) . '
</div>';
	return $__finalCompiled;
}
);