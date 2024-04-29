<?php
// FROM HASH: 064c63d126fa7c96450b8653d8dd4c51
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__vars['availableGenres']);
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
		'name' => 'type_config[available_genres]',
		'value' => $__vars['forum']['type_config']['available_genres'],
		'listclass' => 'field listColumns',
	), $__compilerTemp1, array(
		'label' => 'Available TV genres',
		'explain' => 'You can limit the TV show type by genre in this forum.<br />If you select ALL then any TV genre will be allowed.<br />If you select any other genres then new threads for this forum will be limited to TV shows in those genres.<br />NOTE: If someone tries to post a TV show in a genre that does not have a node assigned to it, then they will receive an error message stating that the genre is not allowed to be posted. So, if you decide to keep TV genres in separate forums, be sure to either create a forum for every genre listed or create one forum set to accept ALL genres. The ALL genre is the last to be considered for a new thread. That way, if someone tries to post a TV show with a different genre for the node they are posting in, it will be automatically posted in the correct node without an error.',
	));
	return $__finalCompiled;
}
);