<?php
// FROM HASH: 232656d16288c266551f085cd6c1b76d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['thread']['Movie'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="structItem-cell structItem-cell--movie">
		<img class="moviePoster" src="' . $__templater->escape($__templater->method($__vars['thread']['Movie'], 'getImageUrl', array('s', ))) . '" />
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="structItem-cell structItem-cell--movie">
		<div class="structItem-iconContainer">
			' . $__templater->func('avatar', array($__vars['thread']['User'], 's', false, array(
			'defaultname' => $__vars['thread']['username'],
		))) . '
			';
		if ($__templater->method($__vars['thread'], 'getUserPostCount', array())) {
			$__finalCompiled .= '
				' . $__templater->func('avatar', array($__vars['xf']['visitor'], 's', false, array(
				'href' => '',
				'class' => 'avatar--separated structItem-secondaryIcon',
				'title' => $__templater->filter('You have posted ' . $__templater->method($__vars['thread'], 'getUserPostCount', array()) . ' message(s) in this thread', array(array('for_attr', array()),), false),
			))) . '
			';
		}
		$__finalCompiled .= '
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);