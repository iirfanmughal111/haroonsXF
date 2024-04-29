<?php
// FROM HASH: c57ed62129a1d25e75247095d265122b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Movie ' . $__templater->escape($__vars['movie']['tmdb_title']) . ' videos');
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

<div class="block">
	' . $__templater->callMacro('snog_movies_videos_macros', 'video_list', array(
		'movie' => $__vars['movie'],
		'videos' => $__vars['videos'],
		'page' => $__vars['page'],
		'hasMore' => $__vars['hasMore'],
	), $__vars) . '
</div>';
	return $__finalCompiled;
}
);