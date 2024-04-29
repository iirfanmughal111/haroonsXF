<?php
// FROM HASH: 476fd095db0aac6f8d49f52452559fa3
return array(
'extends' => function($__templater, array $__vars) { return 'forum_view'; },
'extensions' => array('above_node_list' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
	';
	$__templater->includeCss('snog_tv.less');
	$__finalCompiled .= '
	
	';
	if (!$__templater->test($__vars['forum']['TVForum'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__templater->includeCss('snog_tv.less');
		$__finalCompiled .= '
		';
		if ($__vars['xf']['options']['TvThreads_use_rating'] AND (!$__vars['forum']['TVForum']['tv_parent_id'])) {
			$__finalCompiled .= '
			<div class="block-outer">
				<div class="block-outer-opposite">
					';
			$__vars['rating'] = $__vars['forum']['TVForum']['tv_rating'];
			$__finalCompiled .= '
					';
			$__vars['voteString'] = (($__vars['forum']['TVForum']['tv_votes'] == 1) ? 'Vote' : 'Votes');
			$__finalCompiled .= '
					<div style="margin-bottom:10px;">

						' . $__templater->callMacro('rating_macros', 'stars_text', array(
				'rating' => $__vars['rating'],
				'text' => 'Rating' . ': ' . $__vars['forum']['TVForum']['tv_rating'] . '/5 ' . $__vars['forum']['TVForum']['tv_votes'] . ' ' . $__vars['voteString'],
			), $__vars) . '

						';
			if ($__vars['xf']['visitor']['user_id']) {
				$__finalCompiled .= '
							' . $__templater->button('
								' . 'Add/Change rating' . '
							', array(
					'class' => 'button--link',
					'href' => $__templater->func('link', array('tvshow/rate-show', $__vars['forum'], ), false),
					'overlay' => 'true',
				), '', array(
				)) . '
						';
			}
			$__finalCompiled .= '
					</div>
				</div>
			</div>
		';
		}
		$__finalCompiled .= '

		<div class="block">
			<div class="block-container">
				<div class="block-body block-body--contained">
					<div class="tvblock-poster">
						';
		if ($__vars['forum']['TVForum']['tv_parent_id']) {
			$__finalCompiled .= '
							<img src="' . $__templater->escape($__templater->method($__vars['forum']['TVForum'], 'getSeasonPosterUrl', array())) . '" />
						';
		} else {
			$__finalCompiled .= '
							<img src="' . $__templater->escape($__templater->method($__vars['forum']['TVForum'], 'getForumPosterUrl', array())) . '" />
						';
		}
		$__finalCompiled .= '
					</div>
					<div class="tvblockinfo">
						' . $__templater->escape($__vars['forum']['TVForum']['tv_plot']) . '<br /><br />
						';
		if ($__vars['forum']['TVForum']['tv_genres']) {
			$__finalCompiled .= 'Genre' . ': ' . $__templater->filter($__vars['forum']['TVForum']['tv_genres'], array(array('raw', array()),), true) . '<br />';
		}
		$__finalCompiled .= '
						' . 'First aired' . ': ' . $__templater->escape($__vars['forum']['TVForum']['tv_release']) . '<br />
					</div>
				</div>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
},
'below_thread_list' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '<span class="tvhint">' . 'TV show information provided by <a href="https://themoviedb.org" target="_blank" >The Movie Database</a>' . '</span>';
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . $__templater->renderExtension('above_node_list', $__vars, $__extensions) . '

' . $__templater->renderExtension('below_thread_list', $__vars, $__extensions);
	return $__finalCompiled;
}
);