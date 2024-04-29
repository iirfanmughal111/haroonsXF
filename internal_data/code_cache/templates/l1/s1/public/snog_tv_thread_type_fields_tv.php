<?php
// FROM HASH: 14c7a216c88101f2c61569771c0633a0
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

';
	if (!$__templater->test($__vars['thread']['Forum']['TVForum'], 'empty', array()) AND $__vars['thread']['Forum']['TVForum']['tv_parent_id']) {
		$__finalCompiled .= '
	' . $__templater->formTextBoxRow(array(
			'name' => 'snog_tv_tv_id',
			'value' => $__vars['thread']['TV']['tv_episode'],
			'disabled' => ($__vars['context'] == 'edit'),
		), array(
			'label' => 'Episode number',
			'explain' => 'All you need to enter is the episode number for this season. (EXAMPLE: 5)',
			'rowtype' => $__vars['rowType'],
		)) . '
';
	} else {
		$__finalCompiled .= '
	' . $__templater->formTextBoxRow(array(
			'name' => 'snog_tv_tv_id',
			'value' => $__vars['thread']['TV']['tv_id'],
			'disabled' => ($__vars['context'] == 'edit'),
		), array(
			'label' => 'TMDb TV show link or TV show ID',
			'explain' => 'Don\'t have the TMDb Link or ID for your TV show? Go to <a href="https://themoviedb.org" target="_blank" >The Movie Database</a> and look it up.',
			'rowtype' => $__vars['rowType'],
		)) . '
';
	}
	return $__finalCompiled;
}
);