<?php
// FROM HASH: 1c9a39f095663799b59bd0847685a6b9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['thread']['TV'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="structItem-cell structItem-cell--tv">
		<img class="tvPoster" src="' . $__templater->escape($__templater->method($__vars['thread']['TV'], 'getImageUrl', array('s', ))) . '" />
	</div>
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->includeCss('snog_tv.less');
		$__finalCompiled .= '
	<div class="structItem-cell structItem-cell--tv">
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