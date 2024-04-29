<?php
// FROM HASH: 336622510a4182c696f08e5027df31d4
return array(
'macros' => array('login_filter' => array(
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
			<span class="inputGroup-text">
				' . $__templater->formCheckBox(array(
		'standalone' => 'true',
	), array(array(
		'class' => 'js-filterPrefix',
		'label' => 'Prefix',
		'_type' => 'option',
	))) . '
			</span>
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