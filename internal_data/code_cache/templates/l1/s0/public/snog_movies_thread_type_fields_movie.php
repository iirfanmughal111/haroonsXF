<?php
// FROM HASH: fd2c014b9e9d3f08b6e82f9d7742f5c1
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
		'name' => 'snog_movies_tmdb_id',
		'value' => $__vars['thread']['Movie']['tmdb_id'],
		'disabled' => ($__vars['context'] == 'edit'),
	), array(
		'label' => 'TMDb link or Movie ID',
		'explain' => 'Don\'t have the TMDb Link or ID for your movie? Go to <a href="https://themoviedb.org" target="_blank" >The Movie Database</a> and look it up.',
		'rowtype' => $__vars['rowType'],
	));
	return $__finalCompiled;
}
);