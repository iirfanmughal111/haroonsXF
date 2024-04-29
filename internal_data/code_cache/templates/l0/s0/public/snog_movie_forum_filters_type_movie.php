<?php
// FROM HASH: 9d009f105aa41ea4aad710651643db57
return array(
'extends' => function($__templater, array $__vars) { return 'forum_filters'; },
'extensions' => array('before_date_limit' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
	<div class="menu-row menu-row--separated">
		' . 'Genre' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formTextBox(array(
		'name' => 'genre',
		'value' => ($__vars['filters']['genre'] ? $__vars['filters']['genre'] : ''),
		'maxlength' => '50',
	)) . '
		</div>
	</div>

	<div class="menu-row menu-row--separated">
		' . 'Director' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formTextBox(array(
		'name' => 'director',
		'value' => ($__vars['filters']['director'] ? $__vars['filters']['director'] : ''),
		'maxlength' => '50',
	)) . '
		</div>
	</div>

	<div class="menu-row menu-row--separated">
		' . 'Cast' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formTextBox(array(
		'name' => 'cast',
		'value' => ($__vars['filters']['cast'] ? $__vars['filters']['cast'] : ''),
		'maxlength' => '50',
	)) . '
		</div>
	</div>

	<div class="menu-row menu-row--separated">
		' . 'Movie title' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formTextBox(array(
		'name' => 'movie_title',
		'value' => ($__vars['filters']['movie_title'] ? $__vars['filters']['movie_title'] : ''),
		'maxlength' => '50',
	)) . '
		</div>
	</div>

';
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . $__templater->renderExtension('before_date_limit', $__vars, $__extensions);
	return $__finalCompiled;
}
);