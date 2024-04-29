<?php
// FROM HASH: 7b0a8d9518ded8995c409359e99cb116
return array(
'extends' => function($__templater, array $__vars) { return 'thread_view'; },
'extensions' => array('content_top' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
	';
	$__templater->includeCss('snog_tv.less');
	$__finalCompiled .= '
';
	return $__finalCompiled;
},
'thread_action_buttons' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
	';
	if ((($__templater->func('property', array('snog_tv_posterUpdateButtonPosition', ), false) == 'default') AND ($__vars['thread']['TV'] AND ($__vars['xf']['visitor']['is_admin'] OR $__vars['xf']['visitor']['is_moderator'])))) {
		$__finalCompiled .= '
		' . $__templater->button('
			' . 'Check for new poster' . '
		', array(
			'href' => $__templater->func('link', array('tv/poster', $__vars['thread']['TV'], ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '
	';
	if ($__vars['xf']['options']['TvThreads_update'] AND ((!$__vars['thread']['TV']) AND $__vars['canAddInfo'])) {
		$__finalCompiled .= '
		' . $__templater->button('
			' . 'Add TV show info' . '
		', array(
			'href' => $__templater->func('link', array('tv/add-info', $__vars['thread'], ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '
	' . $__templater->renderExtensionParent($__vars, null, $__extensions) . '
';
	return $__finalCompiled;
},
'above_messages_below_pinned' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
	';
	if (!$__templater->test($__vars['thread']['TV'], 'empty', array()) AND ($__vars['page'] <= 1)) {
		$__finalCompiled .= '
		<span class="tvhint">' . 'Show information in first post provided by <a href="https://themoviedb.org" target="_blank" >The Movie Database</a>' . '</span>
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

' . $__templater->renderExtension('content_top', $__vars, $__extensions) . '

' . $__templater->renderExtension('thread_action_buttons', $__vars, $__extensions) . '

' . $__templater->renderExtension('above_messages_below_pinned', $__vars, $__extensions);
	return $__finalCompiled;
}
);