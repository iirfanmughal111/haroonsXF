<?php
// FROM HASH: 21684d491da6833a813dcc6a6572dabb
return array(
'macros' => array('quick_filter' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'key' => '',
		'ajax' => '',
		'class' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'xf/filter.js',
		'min' => '1',
	));
	$__finalCompiled .= '
	<div class="' . $__templater->escape($__vars['class']) . ' quickFilter u-jsOnly" data-xf-init="filter" data-key="' . $__templater->escape($__vars['key']) . '" data-ajax="' . $__templater->escape($__vars['ajax']) . '">
		<div class="inputGroup inputGroup--inline inputGroup--joined">
			<input type="text" class="input js-filterInput" placeholder="' . $__templater->filter('Filter' . $__vars['xf']['language']['ellipsis'], array(array('for_attr', array()),), true) . '" data-xf-key="' . $__templater->filter('f', array(array('for_attr', array()),), true) . '" />
			<i class="inputGroup-text js-filterClear is-disabled" aria-hidden="true"></i>
		</div>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);