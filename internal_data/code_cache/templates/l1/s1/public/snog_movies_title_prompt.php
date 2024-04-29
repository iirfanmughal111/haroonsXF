<?php
// FROM HASH: 1b9c02bd18bfc1945d3f2340c651124d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('snog_movies.less');
	$__finalCompiled .= '
	
';
	if (!$__vars['xf']['options']['tmdbthreads_mix']) {
		$__finalCompiled .= '
	';
		$__vars['placeholder'] = 'TMDb link or Movie ID';
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__vars['placeholder'] = 'Thread title, TMDb link or Movie ID';
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '
	
' . $__templater->formPrefixInputRow($__vars['prefixes'], array(
		'type' => 'thread',
		'prefix-value' => ($__vars['thread']['prefix_id'] ?: $__vars['forum']['default_prefix_id']),
		'textbox-value' => ($__vars['thread']['title'] ?: $__vars['forum']['draft_thread']['title']),
		'textbox-class' => 'input--title',
		'placeholder' => $__vars['placeholder'],
		'autofocus' => 'autofocus',
		'maxlength' => $__templater->func('max_length', array('XF:Thread', 'title', ), false),
	), array(
		'label' => 'Title',
		'explain' => 'Don\'t have the TMDb Link or ID for your movie? Go to <a href="https://themoviedb.org" target="_blank" >The Movie Database</a> and look it up.',
		'rowtype' => 'fullWidth noLabel',
	));
	return $__finalCompiled;
}
);