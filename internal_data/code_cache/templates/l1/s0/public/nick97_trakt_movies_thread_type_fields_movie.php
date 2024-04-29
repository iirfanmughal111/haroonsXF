<?php
// FROM HASH: d2047909846064889a324ffc8a275e0b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (($__vars['context'] == 'create') AND ($__vars['subContext'] == 'quick')) {
		$__finalCompiled .= '
	';
		$__vars['rowType'] = 'fullWidth noGutter mergeNext';
		$__finalCompiled .= '
';
	} else if (($__vars['context'] == 'edit') AND ($__vars['subContext'] == 'first_post_quick')) {
		$__finalCompiled .= '
	';
		$__vars['rowType'] = 'fullWidth mergeNext';
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__vars['rowType'] = '';
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->formTextBoxRow(array(
		'name' => 'nick97_trakt_movies_tmdb_id',
		'value' => $__vars['thread']['traktMovie']['tmdb_id'],
		'disabled' => ($__vars['context'] == 'edit'),
	), array(
		'label' => 'Trakt link or Movie ID',
		'explain' => 'Don\'t have the Trakt Link or ID for your movie? Go to <a href="https://trakt.tv/movies/trending" target="_blank" >Trakt Movies</a> and look it up.',
		'rowtype' => $__vars['rowType'],
	));
	return $__finalCompiled;
}
);