<?php
// FROM HASH: be68cea7d1a120e69fef2b85faedb6b6
return array(
'macros' => array('item' => array(
'extends' => 'thread_list_macros::item',
'extensions' => array('icon_cell' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
		';
	if (!$__templater->test($__vars['thread']['TV'], 'empty', array())) {
		$__finalCompiled .= '
			' . $__templater->includeTemplate('snog_tv_thread_list_poster', $__vars) . '
		';
	} else {
		$__finalCompiled .= '
			' . $__templater->renderExtensionParent($__vars, null, $__extensions) . '
		';
	}
	$__finalCompiled .= '
	';
	return $__finalCompiled;
},
'before_status_state' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	
	return $__finalCompiled;
},
'status_sticky' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
							';
	if ($__vars['thread']['sticky']) {
		$__finalCompiled .= '
								<li>
									<i class="structItem-status structItem-status--sticky" aria-hidden="true" title="' . $__templater->filter('Sticky', array(array('for_attr', array()),), true) . '"></i>
									<span class="u-srOnly">' . 'Sticky' . '</span>
								</li>
							';
	}
	$__finalCompiled .= '
						';
	return $__finalCompiled;
},
'before_status_watch' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	
	return $__finalCompiled;
},
'before_status_type' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
							';
	if ($__vars['xf']['options']['tmdbthreads_use_rating']) {
		$__finalCompiled .= '
								<ul class="structItem-extraInfo">
									<li>
										' . $__templater->callMacro('rating_macros', 'stars', array(
			'rating' => $__vars['thread']['TV']['tv_rating'],
		), $__vars) . '
									</li>
								</ul>
							';
	}
	$__finalCompiled .= '
						';
	return $__finalCompiled;
},
'thread_type_redirect' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
								<li>
									<i class="structItem-status structItem-status--redirect" aria-hidden="true" title="' . $__templater->filter('Redirect', array(array('for_attr', array()),), true) . '"></i>
									<span class="u-srOnly">' . 'Redirect' . '</span>
								</li>
							';
	return $__finalCompiled;
},
'thread_type_question_solved' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
								<li>
									<i class="structItem-status structItem-status--solved" aria-hidden="true" title="' . $__templater->filter('Solved', array(array('for_attr', array()),), true) . '"></i>
									<span class="u-srOnly">' . 'Solved' . '</span>
								</li>
							';
	return $__finalCompiled;
},
'thread_type_icon' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
								';
	if ($__vars['thread']['discussion_type'] != 'discussion') {
		$__finalCompiled .= '
									';
		$__vars['threadTypeHandler'] = $__templater->method($__vars['thread'], 'getTypeHandler', array());
		$__finalCompiled .= '
									';
		if ($__templater->method($__vars['threadTypeHandler'], 'getTypeIconClass', array())) {
			$__finalCompiled .= '
										<li>
											';
			$__vars['threadTypePhrase'] = $__templater->method($__vars['threadTypeHandler'], 'getTypeTitle', array());
			$__finalCompiled .= '
											' . $__templater->fontAwesome($__templater->escape($__templater->method($__vars['threadTypeHandler'], 'getTypeIconClass', array())), array(
				'class' => 'structItem-status',
				'title' => $__templater->filter($__vars['threadTypePhrase'], array(array('for_attr', array()),), false),
			)) . '
											<span class="u-srOnly">' . $__templater->escape($__vars['threadTypePhrase']) . '</span>
										</li>
									';
		}
		$__finalCompiled .= '
								';
	}
	$__finalCompiled .= '
							';
	return $__finalCompiled;
},
'statuses' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
						';
	if (($__templater->func('property', array('reactionSummaryOnLists', ), false) == 'status') AND $__vars['thread']['first_post_reactions']) {
		$__finalCompiled .= '
							<li>' . $__templater->func('reactions_summary', array($__vars['thread']['first_post_reactions'])) . '</li>
						';
	}
	$__finalCompiled .= '
						' . $__templater->renderExtension('before_status_state', $__vars, $__extensions) . '
						';
	if ($__vars['thread']['discussion_state'] == 'moderated') {
		$__finalCompiled .= '
							<li>
								<i class="structItem-status structItem-status--moderated" aria-hidden="true" title="' . $__templater->filter('Awaiting approval', array(array('for_attr', array()),), true) . '"></i>
								<span class="u-srOnly">' . 'Awaiting approval' . '</span>
							</li>
						';
	}
	$__finalCompiled .= '
						';
	if ($__vars['thread']['discussion_state'] == 'deleted') {
		$__finalCompiled .= '
							<li>
								<i class="structItem-status structItem-status--deleted" aria-hidden="true" title="' . $__templater->filter('Deleted', array(array('for_attr', array()),), true) . '"></i>
								<span class="u-srOnly">' . 'Deleted' . '</span>
							</li>
						';
	}
	$__finalCompiled .= '
						';
	if (!$__vars['thread']['discussion_open']) {
		$__finalCompiled .= '
							<li>
								<i class="structItem-status structItem-status--locked" aria-hidden="true" title="' . $__templater->filter('Locked', array(array('for_attr', array()),), true) . '"></i>
								<span class="u-srOnly">' . 'Locked' . '</span>
							</li>
						';
	}
	$__finalCompiled .= '

						' . $__templater->renderExtension('status_sticky', $__vars, $__extensions) . '

						' . $__templater->renderExtension('before_status_watch', $__vars, $__extensions) . '
						';
	if ($__vars['showWatched'] AND $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
							';
		if ($__vars['thread']['Watch'][$__vars['xf']['visitor']['user_id']]) {
			$__finalCompiled .= '
								<li>
									<i class="structItem-status structItem-status--watched" aria-hidden="true" title="' . $__templater->filter('Thread watched', array(array('for_attr', array()),), true) . '"></i>
									<span class="u-srOnly">' . 'Thread watched' . '</span>
								</li>
								';
		} else if ((!$__vars['forum']) AND $__vars['thread']['Forum']['Watch'][$__vars['xf']['visitor']['user_id']]) {
			$__finalCompiled .= '
								<li>
									<i class="structItem-status structItem-status--watched" aria-hidden="true" title="' . $__templater->filter('Forum watched', array(array('for_attr', array()),), true) . '"></i>
									<span class="u-srOnly">' . 'Forum watched' . '</span>
								</li>
							';
		}
		$__finalCompiled .= '
						';
	}
	$__finalCompiled .= '

						' . $__templater->renderExtension('before_status_type', $__vars, $__extensions) . '
						
						';
	if ($__vars['thread']['discussion_type'] == 'redirect') {
		$__finalCompiled .= '
							' . $__templater->renderExtension('thread_type_redirect', $__vars, $__extensions) . '
						';
	} else if (($__vars['thread']['discussion_type'] == 'question') AND $__vars['thread']['type_data']['solution_post_id']) {
		$__finalCompiled .= '
							' . $__templater->renderExtension('thread_type_question_solved', $__vars, $__extensions) . '
						';
	} else if ((!$__vars['forum']) OR ($__vars['forum']['forum_type_id'] == 'discussion')) {
		$__finalCompiled .= '
							' . $__templater->renderExtension('thread_type_icon', $__vars, $__extensions) . '
						';
	}
	$__finalCompiled .= '
					';
	return $__finalCompiled;
},
'main_cell' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
		';
	if (!$__templater->test($__vars['thread']['TV'], 'empty', array())) {
		$__finalCompiled .= '
			<div class="structItem-cell structItem-cell--main" data-xf-init="touch-proxy">
				';
		$__compilerTemp1 = '';
		$__compilerTemp1 .= '
					' . $__templater->renderExtension('statuses', $__vars, $__extensions) . '
					';
		if (strlen(trim($__compilerTemp1)) > 0) {
			$__finalCompiled .= '
					<ul class="structItem-statuses">
					' . $__compilerTemp1 . '
					</ul>
				';
		}
		$__finalCompiled .= '

				<div class="structItem-title">
					';
		$__vars['canPreview'] = $__templater->method($__vars['thread'], 'canPreview', array());
		$__finalCompiled .= '
					';
		if ($__vars['thread']['prefix_id']) {
			$__finalCompiled .= '
						';
			if ($__vars['forum']) {
				$__finalCompiled .= '
							<a href="' . $__templater->func('link', array('forums', $__vars['forum'], array('prefix_id' => $__vars['thread']['prefix_id'], ), ), true) . '" class="labelLink" rel="nofollow">' . $__templater->func('prefix', array('thread', $__vars['thread'], 'html', '', ), true) . '</a>
						';
			} else {
				$__finalCompiled .= '
							' . $__templater->func('prefix', array('thread', $__vars['thread'], 'html', '', ), true) . '
						';
			}
			$__finalCompiled .= '
					';
		}
		$__finalCompiled .= '
					<a href="' . $__templater->func('link', array('threads' . (($__templater->method($__vars['thread'], 'isUnread', array()) AND (!$__vars['forceRead'])) ? '/unread' : ''), $__vars['thread'], ), true) . '" class="" data-tp-primary="on" data-xf-init="' . ($__vars['canPreview'] ? 'preview-tooltip' : '') . '" data-preview-url="' . ($__vars['canPreview'] ? $__templater->func('link', array('threads/preview', $__vars['thread'], ), true) : '') . '">' . $__templater->escape($__vars['thread']['title']) . '</a>
				</div>

				<div class="structItem-minor">
					';
		$__compilerTemp2 = '';
		$__compilerTemp2 .= '
							';
		if (($__templater->func('property', array('reactionSummaryOnLists', ), false) == 'minor_opposite') AND $__vars['thread']['first_post_reactions']) {
			$__compilerTemp2 .= '
								<li>' . $__templater->func('reactions_summary', array($__vars['thread']['first_post_reactions'])) . '</li>
							';
		}
		$__compilerTemp2 .= '
							';
		if ($__vars['extraInfo']) {
			$__compilerTemp2 .= '
								<li>' . $__templater->escape($__vars['extraInfo']) . '</li>
							';
		} else if ($__vars['allowEdit'] AND ($__templater->method($__vars['thread'], 'canEdit', array()) AND $__templater->method($__vars['thread'], 'canUseInlineModeration', array()))) {
			$__compilerTemp2 .= '
								';
			if ((!$__vars['allowInlineMod']) OR (!$__vars['forum'])) {
				$__compilerTemp2 .= '
									';
				$__vars['editParams'] = array('_xfNoInlineMod' => ((!$__vars['allowInlineMod']) ? 1 : null), '_xfForumName' => ((!$__vars['forum']) ? 1 : 0), );
				$__compilerTemp2 .= '
								';
			} else {
				$__compilerTemp2 .= '
									';
				$__vars['editParams'] = array();
				$__compilerTemp2 .= '
								';
			}
			$__compilerTemp2 .= '
								';
			if ($__vars['thread']['discussion_type'] != 'redirect') {
				$__compilerTemp2 .= '
									<li class="structItem-extraInfoMinor">
										<a href="' . $__templater->func('link', array('threads/edit', $__vars['thread'], ), true) . '" data-xf-click="overlay" data-cache="false" data-href="' . $__templater->func('link', array('threads/edit', $__vars['thread'], $__vars['editParams'], ), true) . '">
											' . 'Edit' . '
										</a>
									</li>
								';
			}
			$__compilerTemp2 .= '
							';
		}
		$__compilerTemp2 .= '
							';
		if ($__vars['chooseName']) {
			$__compilerTemp2 .= '
								<li>' . $__templater->formCheckBox(array(
				'standalone' => 'true',
			), array(array(
				'name' => $__vars['chooseName'] . '[]',
				'value' => $__vars['thread']['thread_id'],
				'class' => 'js-chooseItem',
				'_type' => 'option',
			))) . '</li>
							';
		} else if ($__vars['allowInlineMod'] AND $__templater->method($__vars['thread'], 'canUseInlineModeration', array())) {
			$__compilerTemp2 .= '
								<li>' . $__templater->formCheckBox(array(
				'standalone' => 'true',
			), array(array(
				'value' => $__vars['thread']['thread_id'],
				'class' => 'js-inlineModToggle',
				'data-xf-init' => 'tooltip',
				'title' => 'Select for moderation',
				'label' => 'Select for moderation',
				'hiddenlabel' => 'true',
				'_type' => 'option',
			))) . '</li>
							';
		}
		$__compilerTemp2 .= '
						';
		if (strlen(trim($__compilerTemp2)) > 0) {
			$__finalCompiled .= '
						<ul class="structItem-extraInfo">
						' . $__compilerTemp2 . '
						</ul>
					';
		}
		$__finalCompiled .= '

					';
		if ($__vars['thread']['discussion_state'] == 'deleted') {
			$__finalCompiled .= '
						';
			if ($__vars['extraInfo']) {
				$__finalCompiled .= '<span class="structItem-extraInfo">' . $__templater->escape($__vars['extraInfo']) . '</span>';
			}
			$__finalCompiled .= '

						' . $__templater->callMacro('deletion_macros', 'notice', array(
				'log' => $__vars['thread']['DeletionLog'],
			), $__vars) . '
					';
		} else {
			$__finalCompiled .= '
						<div>
							' . $__templater->callMacro(null, 'tv_info', array(
				'thread' => $__vars['thread'],
				'onlyInclude' => $__vars['xf']['options']['TvThreads_threadListInfo'],
			), $__vars) . '
							<ul class="structItem-parts">
								<li>' . $__templater->func('username_link', array($__vars['thread']['User'], false, array(
				'defaultname' => $__vars['thread']['username'],
			))) . '</li>
								<li class="structItem-startDate"><a href="' . $__templater->func('link', array('threads', $__vars['thread'], ), true) . '" rel="nofollow">' . $__templater->func('date_dynamic', array($__vars['thread']['post_date'], array(
			))) . '</a></li>
								';
			if (!$__vars['forum']) {
				$__finalCompiled .= '
									<li><a href="' . $__templater->func('link', array('forums', $__vars['thread']['Forum'], ), true) . '">' . $__templater->escape($__vars['thread']['Forum']['title']) . '</a></li>
								';
			}
			$__finalCompiled .= '
							</ul>
						</div>

						';
			if (($__vars['thread']['discussion_type'] != 'redirect') AND (($__vars['thread']['reply_count'] >= $__vars['xf']['options']['messagesPerPage']) AND $__vars['xf']['options']['lastPageLinks'])) {
				$__finalCompiled .= '
							<span class="structItem-pageJump">
							';
				$__compilerTemp3 = $__templater->func('last_pages', array($__vars['thread']['reply_count'] + 1, $__vars['xf']['options']['messagesPerPage'], $__vars['xf']['options']['lastPageLinks'], ), false);
				if ($__templater->isTraversable($__compilerTemp3)) {
					foreach ($__compilerTemp3 AS $__vars['p']) {
						$__finalCompiled .= '
								<a href="' . $__templater->func('link', array('threads', $__vars['thread'], array('page' => $__vars['p'], ), ), true) . '">' . $__templater->escape($__vars['p']) . '</a>
							';
					}
				}
				$__finalCompiled .= '
							</span>
						';
			}
			$__finalCompiled .= '
					';
		}
		$__finalCompiled .= '
					
				</div>
			</div>
		';
	} else {
		$__finalCompiled .= '
			' . $__templater->renderExtensionParent($__vars, null, $__extensions) . '
		';
	}
	$__finalCompiled .= '
	';
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->renderExtension('icon_cell', $__vars, $__extensions) . '
	
	' . $__templater->renderExtension('main_cell', $__vars, $__extensions) . '

';
	return $__finalCompiled;
}
),
'tv_info' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'thread' => '!',
		'onlyInclude' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . '
	';
	if ((!$__vars['onlyInclude']) OR $__vars['onlyInclude']['title']) {
		$__finalCompiled .= '<b>' . 'Title' . ':</b> ' . $__templater->escape($__vars['thread']['TV']['tv_title']) . '<br />';
	}
	$__finalCompiled .= '
	';
	if (((!$__vars['onlyInclude']) OR $__vars['onlyInclude']['genres']) AND $__vars['thread']['TV']['tv_genres']) {
		$__finalCompiled .= '<b>' . 'Genre' . ':</b> ' . $__templater->escape($__vars['thread']['TV']['tv_genres']) . '<br />';
	}
	$__finalCompiled .= '
	';
	if (((!$__vars['onlyInclude']) OR $__vars['onlyInclude']['director']) AND $__vars['thread']['TV']['tv_director']) {
		$__finalCompiled .= '<b>' . 'Creator' . ':</b> ' . $__templater->escape($__vars['thread']['TV']['tv_director']) . '<br />';
	}
	$__finalCompiled .= '
	';
	if (((!$__vars['onlyInclude']) OR $__vars['onlyInclude']['first_air_date']) AND $__vars['thread']['TV']['first_air_date']) {
		$__finalCompiled .= '<b>' . 'First aired' . ':</b> ' . $__templater->func('date', array($__vars['thread']['TV']['first_air_date'], ), true) . '<br />';
	}
	$__finalCompiled .= '
	';
	if (((!$__vars['onlyInclude']) OR $__vars['onlyInclude']['last_air_date']) AND $__vars['thread']['TV']['last_air_date']) {
		$__finalCompiled .= '<b>' . 'Last air date' . ':</b> ' . $__templater->func('date', array($__vars['thread']['TV']['last_air_date'], ), true) . '<br />';
	}
	$__finalCompiled .= '
	';
	if (((!$__vars['onlyInclude']) OR $__vars['onlyInclude']['status']) AND !$__templater->test($__vars['thread']['TV']['status'], 'empty', array())) {
		$__finalCompiled .= '<b>' . 'Show status' . ':</b> ' . $__templater->escape($__vars['thread']['TV']['status']) . '<br />';
	}
	$__finalCompiled .= '
	';
	if (((!$__vars['onlyInclude']) OR $__vars['onlyInclude']['plot']) AND !$__templater->test($__vars['thread']['TV']['tv_plot'], 'empty', array())) {
		$__finalCompiled .= '<b>' . 'Overview' . ':</b> ' . $__templater->escape($__vars['thread']['TV']['tv_plot']) . '<br />';
	}
	$__finalCompiled .= '
	' . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);