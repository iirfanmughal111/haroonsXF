<?php
// FROM HASH: 79bff27d82895357602f745ca30ffdea
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated ' . ($__templater->method($__vars['thread'], 'isIgnored', array()) ? 'is-ignored' : '') . ' js-inlineModContainer" data-author="' . ($__templater->escape($__vars['thread']['User']['username']) ?: $__templater->escape($__vars['thread']['username'])) . '">
	';
	$__templater->includeCss('snog_movies.less');
	$__finalCompiled .= '
	
	<div class="contentRow ' . ((!$__templater->method($__vars['thread'], 'isVisible', array())) ? 'is-deleted' : '') . '">
		<span class="contentRow-figure">
			' . $__templater->includeTemplate('snog_movie_search_result_thread_poster', $__vars) . '
		</span>
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('threads', $__vars['thread'], ), true) . '">' . ($__templater->func('prefix', array('thread', $__vars['thread'], ), true) . $__templater->func('highlight', array($__vars['thread']['title'], $__vars['options']['term'], ), true)) . '</a>
			</h3>

			<div>
				' . $__templater->callMacro('snog_movies_thread_list_movie_macros', 'movie_info', array(
		'thread' => $__vars['thread'],
		'onlyInclude' => $__vars['xf']['options']['tmdbthreads_threadListInfo'],
	), $__vars) . '
			</div>

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					';
	if (($__vars['options']['mod'] == 'thread') AND $__templater->method($__vars['thread'], 'canUseInlineModeration', array())) {
		$__finalCompiled .= '
						<li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['thread']['thread_id'],
			'class' => 'js-inlineModToggle',
			'data-xf-init' => 'tooltip',
			'title' => 'Select for moderation',
			'_type' => 'option',
		))) . '</li>
					';
	}
	$__finalCompiled .= '
					<li>' . $__templater->func('username_link', array($__vars['thread']['User'], false, array(
		'defaultname' => $__vars['thread']['username'],
	))) . '</li>
					<li>' . 'Thread' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['thread']['post_date'], array(
	))) . '</li>
					';
	if ($__vars['xf']['options']['enableTagging'] AND $__vars['thread']['tags']) {
		$__finalCompiled .= '
						<li>
							' . $__templater->callMacro('tag_macros', 'simple_list', array(
			'tags' => $__vars['thread']['tags'],
			'containerClass' => 'contentRow-minor',
			'highlightTerm' => ($__vars['options']['tag'] ?: $__vars['options']['term']),
		), $__vars) . '
						</li>
					';
	}
	$__finalCompiled .= '
					<li>' . 'Replies' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['thread']['reply_count'], array(array('number', array()),), true) . '</li>
					<li>' . 'Forum' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('forums', $__vars['thread']['Forum'], ), true) . '">' . $__templater->escape($__vars['thread']['Forum']['title']) . '</a></li>
				</ul>
			</div>
		</div>
	</div>
</li>';
	return $__finalCompiled;
}
);