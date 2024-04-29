<?php
// FROM HASH: 551620fe99a206f7b30d90ca9f47615c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['stats']) {
		$__finalCompiled .= '
	' . $__templater->callMacro('nick97_watch_list_movies_macro', 'stats', array(
			'stats' => $__vars['stats'],
		), $__vars) . '
	';
	} else {
		$__finalCompiled .= '

	';
		if ($__vars['statsLimit']) {
			$__finalCompiled .= '
		<div class="blockMessage">' . 'This member limits who may view their stats.' . '</div>
		';
		} else {
			$__finalCompiled .= '
		<div class="blockMessage">' . 'This member have no any stats.' . '</div>
	';
		}
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__vars['limit']) {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'This member limits who may view their watch list.' . '</div>

	';
	} else {
		$__finalCompiled .= '
	' . $__templater->callMacro('nick97_watch_list_movies_macro', 'movies', array(
			'movies' => $__vars['movies'],
		), $__vars) . '

	' . $__templater->callMacro('nick97_watch_list_movies_macro', 'tvShow', array(
			'tvShows' => $__vars['tvShows'],
		), $__vars) . '
';
	}
	return $__finalCompiled;
}
);