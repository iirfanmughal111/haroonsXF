<?php
// FROM HASH: 11596f182e6982c6db55d7514da942f8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__vars['xf']['options']['TvThreads_mix']) {
		$__finalCompiled .= '
	';
		$__vars['placeholder'] = 'TMDb TV show link or TV show ID';
		$__finalCompiled .= '
	';
		$__vars['titleholder'] = 'Post a new TV show';
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__vars['placeholder'] = 'TMDb TV show link or TV show ID';
		$__finalCompiled .= '
	';
		$__vars['titleholder'] = 'Post a new thread or TV show';
		$__finalCompiled .= '	
';
	}
	$__finalCompiled .= '

' . $__templater->formPrefixInput($__templater->method($__vars['forum'], 'getUsablePrefixes', array()), array(
		'maxlength' => $__templater->func('max_length', array('XF.Thread', 'title', ), false),
		'placeholder' => $__vars['placeholder'],
		'title' => $__vars['titleholder'],
		'prefix-value' => $__vars['forum']['default_prefix_id'],
		'type' => 'thread',
		'data-xf-init' => 'tooltip',
	)) . '

<span class="tvhint">' . 'Don\'t have the TMDb Link or ID for your TV show? Go to <a href="https://themoviedb.org" target="_blank" >The Movie Database</a> and look it up.' . '</span>';
	return $__finalCompiled;
}
);