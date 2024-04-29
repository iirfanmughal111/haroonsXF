<?php
// FROM HASH: 8846ed4cc80e0360436721c10143f718
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<br>

';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('nick97_watch_list', 'view_own_stats', )) AND $__vars['stats']) {
		$__finalCompiled .= '

' . $__templater->callMacro('nick97_watch_list_movies_macro', 'stats', array(
			'stats' => $__vars['stats'],
		), $__vars) . '
	
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('nick97_watch_list_movies_macro', 'movies', array(
		'movies' => $__vars['movies'],
	), $__vars) . '

' . $__templater->callMacro('nick97_watch_list_movies_macro', 'tvShow', array(
		'tvShows' => $__vars['tvShows'],
	), $__vars);
	return $__finalCompiled;
}
);