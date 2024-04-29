<?php
// FROM HASH: 994b22284ff5f39737cfc543aea029cd
return array(
'extends' => function($__templater, array $__vars) { return 'thread_view'; },
'extensions' => array('content_top' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
	';
	$__templater->includeCss('snog_movies.less');
	$__finalCompiled .= '
';
	return $__finalCompiled;
},
'thread_action_buttons' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
	';
	if ((($__templater->func('property', array('snog_movies_posterUpdateButtonPosition', ), false) == 'default') AND ($__vars['thread']['Movie'] AND ($__vars['xf']['visitor']['is_admin'] OR $__vars['xf']['visitor']['is_moderator'])))) {
		$__finalCompiled .= '
		' . $__templater->button('
			' . 'Check for new poster' . '
		', array(
			'href' => $__templater->func('link', array('movies/poster', $__vars['thread']['Movie'], ), false),
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
	if (!$__templater->test($__vars['thread']['Movie'], 'empty', array()) AND ($__vars['page'] <= 1)) {
		$__finalCompiled .= '
		<span class="moviehint">' . 'Movie information in first post provided by <a href="https://themoviedb.org" target="_blank" >The Movie Database</a>' . '</span>
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