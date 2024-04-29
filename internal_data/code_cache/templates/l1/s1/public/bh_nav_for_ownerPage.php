<?php
// FROM HASH: 19d52c8718bfbc62d6dafde7230581af
return array(
'macros' => array('navigation' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'pageSelected' => '!',
		'total' => '',
		'route' => '',
		'brand' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->func('property', array('findThreadsNavStyle', ), false) == 'tabs') {
		$__finalCompiled .= '
		<div class="tabs tabs--standalone">
			<div class="hScroller" data-xf-init="h-scroller">
				<span class="hScroller-scroll">
					' . $__templater->callMacro(null, 'links', array(
			'pageSelected' => $__vars['pageSelected'],
			'baseClass' => 'tabs-tab',
			'selectedClass' => 'is-active',
			'total' => $__vars['total'],
			'route' => $__vars['route'],
			'brand' => $__vars['brand'],
		), $__vars) . '
				</span>
			</div>
		</div>
	';
	} else {
		$__finalCompiled .= '
		';
		$__templater->modifySideNavHtml(null, '
			<div class="block">
				<div class="block-container">
					<h3 class="block-header">' . 'Brand list' . '</h3>
					<div class="block-body">
						' . $__templater->callMacro(null, 'links', array(
			'pageSelected' => $__vars['pageSelected'],
			'baseClass' => 'blockLink',
			'selectedClass' => 'is-selected',
			'total' => $__vars['total'],
			'route' => $__vars['route'],
			'brand' => $__vars['brand'],
		), $__vars) . '
					</div>
				</div>
			</div>

		', 'replace');
		$__finalCompiled .= '
		';
		$__templater->setPageParam('sideNavTitle', 'bh_item_lists');
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'links' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'pageSelected' => '!',
		'baseClass' => '!',
		'selectedClass' => '!',
		'total' => '',
		'route' => '',
		'brand' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<a class="' . $__templater->escape($__vars['baseClass']) . ' ' . (($__vars['pageSelected'] == 'all') ? $__templater->escape($__vars['selectedClass']) : '') . '"
		href="' . $__templater->func('link', array($__vars['route'], $__vars['brand'], ), true) . '" rel="nofollow">' . 'All' . ' (' . $__templater->escape($__vars['total']) . ') </a>
	
	<a class="' . $__templater->escape($__vars['baseClass']) . ' ' . (($__vars['pageSelected'] == 'view_count') ? $__templater->escape($__vars['selectedClass']) : '') . '"
		href="' . $__templater->func('link', array($__vars['route'], $__vars['brand'], array('type' => 'view_count', ), ), true) . '" rel="nofollow">' . 'Most Viewed' . '</a>
	
	<a class="' . $__templater->escape($__vars['baseClass']) . ' ' . (($__vars['pageSelected'] == 'discussion_count') ? $__templater->escape($__vars['selectedClass']) : '') . '"
		href="' . $__templater->func('link', array($__vars['route'], $__vars['brand'], array('type' => 'discussion_count', ), ), true) . '" rel="nofollow">' . 'Most Discussed' . '</a>
	
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);