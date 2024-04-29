<?php
// FROM HASH: 6a95e0b9d1336af6e15696711a284324
return array(
'macros' => array('item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'post' => '!',
		'forum' => '',
		'forceRead' => false,
		'showWatched' => true,
		'allowInlineMod' => true,
		'chooseName' => '',
		'thread' => '',
		'extraInfo' => '',
		'allowEdit' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeCss('structured_list.less');
	$__finalCompiled .= '

	<div class="structItem structItem--thread' . ($__vars['post']['Thread']['prefix_id'] ? (' is-prefix' . $__templater->escape($__vars['post']['Thread']['prefix_id'])) : '') . ($__templater->method($__vars['post']['Thread'], 'isIgnored', array()) ? ' is-ignored' : '') . ($__templater->method($__vars['post']['Thread'], 'isUnread', array()) ? ' is-unread' : '') . (($__vars['post']['Thread']['discussion_state'] == 'moderated') ? ' is-moderated' : '') . (($__vars['post']['Thread']['discussion_state'] == 'deleted') ? ' is-deleted' : '') . ' js-inlineModContainer js-threadListItem-' . $__templater->escape($__vars['post']['Thread']['thread_id']) . '" data-author="' . ($__templater->escape($__vars['post']['Thread']['User']['username']) ?: $__templater->escape($__vars['post']['Thread']['username'])) . '">

	
		<div class="structItem-cell structItem-cell--icon">
			<div class="structItem-iconContainer">
				' . $__templater->func('avatar', array($__vars['post']['Thread']['User'], 's', false, array(
		'defaultname' => $__vars['post']['Thread']['username'],
	))) . '
				
			</div>
		</div>



		<div class="structItem-cell structItem-cell--main" data-xf-init="touch-proxy">

			<div class="structItem-title">

				
		' . '
		
				
				
				
				';
	$__vars['canPreview'] = $__templater->method($__vars['post']['Thread'], 'canPreview', array());
	$__finalCompiled .= '
				';
	if ($__vars['post']['Thread']['prefix_id']) {
		$__finalCompiled .= '
					';
		if ($__vars['forum']) {
			$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('forums', $__vars['forum'], array('prefix_id' => $__vars['post']['Thread']['prefix_id'], ), ), true) . '" class="labelLink" rel="nofollow">' . $__templater->func('prefix', array('thread', $__vars['thread'], 'html', '', ), true) . '</a>
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
				<a href="' . $__templater->func('link', array('posts/', $__vars['post'], ), true) . '" class="" data-tp-primary="on" data-xf-init="' . ($__vars['canPreview'] ? 'preview-tooltip' : '') . '" data-preview-url="' . ($__vars['canPreview'] ? $__templater->func('link', array('threads/preview', $__vars['post']['Thread'], ), true) : '') . '">' . $__templater->escape($__vars['post']['Thread']['title']) . '</a> - <a href="' . $__templater->func('link', array('forums/', $__vars['post']['Thread']['Forum'], ), true) . '" class="" data-tp-primary="on" data-xf-init="' . ($__vars['canPreview'] ? 'preview-tooltip' : '') . '" >' . $__templater->escape($__vars['post']['Thread']['Forum']['title']) . '</a>
			</div>

			<div class="structItem-minor">

					<ul class="structItem-parts">
							<li><p >
								
								';
	if ($__vars['xf']['visitor']['is_admin']) {
		$__finalCompiled .= '
									' . $__templater->func('snippet', array($__vars['post']['message'], $__vars['xf']['options']['xb_number_of_characters'], array('stripQuote' => true, ), ), true) . '	
								';
	} else {
		$__finalCompiled .= '
									' . $__templater->func('bb_code', array($__vars['post']['message'], 'post', $__vars['post'], ), true) . '
								';
	}
	$__finalCompiled .= '		
							
								</p>
						
						</li>
					</ul>

					
			
			</div>
		</div>

		<div class="structItem-cell structItem-cell--latest">
			';
	if ($__vars['post']['Thread']['discussion_type'] == 'redirect') {
		$__finalCompiled .= '
				' . 'N/A' . '
			';
	} else {
		$__finalCompiled .= '
				<a href="#" rel="nofollow"
				   >
					';
		if ($__templater->method($__vars['xf']['visitor'], 'isIgnoring', array($__vars['post']['user_id'], ))) {
			$__finalCompiled .= '
						' . 'Ignored member' . '
					';
		} else {
			$__finalCompiled .= '
						' . $__templater->func('avatar', array($__vars['post']['User'], 'xxs', false, array(
				'defaultname' => $__vars['post']['username'],
			))) . '
						' . $__templater->func('username_link', array($__vars['post']['User'], false, array(
				'defaultname' => $__vars['post']['username'],
			))) . '
					';
		}
		$__finalCompiled .= '
					
					
					
				</a>
				<div class="structItem-minor">
					' . $__templater->func('date_dynamic', array($__vars['post']['Thread']['last_post_date'], array(
			'class' => 'structItem-latestDate',
		))) . '
				</div>
			';
	}
	$__finalCompiled .= '
		</div>

	

	</div>
';
	return $__finalCompiled;
}
),
'item_new_posts' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'thread' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="contentRow">
		<div class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['post']['Thread']['LastPoster'], 'xxs', false, array(
		'defaultname' => $__vars['post']['Thread']['last_post_username'],
	))) . '
		</div>
		<div class="contentRow-main contentRow-main--close">
			';
	if ($__templater->method($__vars['post']['Thread'], 'isUnread', array())) {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('threads/unread', $__vars['thread'], ), true) . '">' . $__templater->func('prefix', array('thread', $__vars['thread'], ), true) . $__templater->escape($__vars['post']['Thread']['title']) . '</a>
			';
	} else {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('threads/post', $__vars['thread'], array('post_id' => $__vars['post']['Thread']['last_post_id'], ), ), true) . '">' . $__templater->func('prefix', array('thread', $__vars['thread'], ), true) . $__templater->escape($__vars['post']['Thread']['title']) . '</a>
			';
	}
	$__finalCompiled .= '

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					<li>' . 'Latest: ' . $__templater->escape($__vars['post']['Thread']['last_post_cache']['username']) . '' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['post']['Thread']['last_post_date'], array(
	))) . '</li>
				</ul>
			</div>
			<div class="contentRow-minor contentRow-minor--hideLinks">
				<a href="' . $__templater->func('link', array('forums', $__vars['post']['Thread']['Forum'], ), true) . '">' . $__templater->escape($__vars['post']['Thread']['Forum']['title']) . '</a>
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'item_new_threads' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'thread' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="contentRow">
		<div class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['post']['Thread']['User'], 'xxs', false, array(
		'defaultname' => $__vars['post']['Thread']['username'],
	))) . '
		</div>
		<div class="contentRow-main contentRow-main--close">
			<a href="' . $__templater->func('link', array('threads', $__vars['thread'], ), true) . '">' . $__templater->func('prefix', array('thread', $__vars['thread'], ), true) . $__templater->escape($__vars['post']['Thread']['title']) . '</a>

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					<li>' . 'Started by ' . $__templater->escape($__vars['post']['Thread']['username']) . '' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['post']['Thread']['post_date'], array(
	))) . '</li>
					<li>' . 'Replies' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['post']['Thread']['reply_count'], array(array('number_short', array()),), true) . '</li>
				</ul>
			</div>
			<div class="contentRow-minor contentRow-minor--hideLinks">
				<a href="' . $__templater->func('link', array('forums', $__vars['post']['Thread']['Forum'], ), true) . '">' . $__templater->escape($__vars['post']['Thread']['Forum']['title']) . '</a>
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'quick_thread' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'forum' => '!',
		'page' => '1',
		'order' => 'last_post_date',
		'direction' => 'desc',
		'prefixes' => array(),
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeCss('structured_list.less');
	$__finalCompiled .= '

	';
	if ($__templater->method($__vars['forum'], 'canCreateThread', array()) OR $__templater->method($__vars['forum'], 'canCreateThreadPreReg', array())) {
		$__finalCompiled .= '

		';
		$__templater->includeJs(array(
			'src' => 'xf/thread.js',
			'min' => '1',
		));
		$__finalCompiled .= '

		';
		$__vars['inlineMode'] = ((($__vars['page'] == 1) AND (($__vars['order'] == 'last_post_date') AND ($__vars['direction'] == 'desc'))) ? true : false);
		$__finalCompiled .= '

		' . $__templater->form('

	
			<div class="structItem-cell structItem-cell--icon">
				<div class="structItem-iconContainer">
					' . $__templater->func('avatar', array($__vars['xf']['visitor'], 's', false, array(
		))) . '
				</div>
			</div>
	


			<div class="structItem-cell structItem-cell--newThread js-prefixListenContainer">

				' . $__templater->formRow('

					' . $__templater->formPrefixInput($__vars['prefixes'], array(
			'maxlength' => $__templater->func('max_length', array('XF:Thread', 'title', ), false),
			'placeholder' => $__vars['forum']['thread_prompt'],
			'title' => 'Post a new thread in this forum',
			'prefix-value' => $__vars['forum']['default_prefix_id'],
			'type' => 'thread',
			'data-xf-init' => 'tooltip',
			'rows' => '1',
			'help-href' => $__templater->func('link', array('forums/prefix-help', $__vars['forum'], ), false),
			'help-skip-initial' => true,
		)) . '
				', array(
			'rowtype' => 'noGutter noLabel fullWidth noPadding mergeNext',
			'label' => 'Title',
		)) . '

				<div class="js-quickThreadFields inserter-container is-hidden"><!--' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '--></div>
			</div>


		', array(
			'action' => $__templater->func('link', array('forums/post-thread', $__vars['forum'], array('inline-mode' => $__vars['inlineMode'], ), ), false),
			'class' => 'structItem',
			'ajax' => 'true',
			'draft' => $__templater->func('link', array('forums/draft', $__vars['forum'], ), false),
			'data-xf-init' => 'quick-thread',
			'data-focus-activate' => '.js-titleInput',
			'data-focus-activate-href' => $__templater->func('link', array('forums/post-thread', $__vars['forum'], array('inline-mode' => true, ), ), false),
			'data-focus-activate-target' => '.js-quickThreadFields',
			'data-insert-target' => '.js-threadList',
			'data-replace-target' => '.js-emptyThreadList',
		)) . '
	';
	}
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);