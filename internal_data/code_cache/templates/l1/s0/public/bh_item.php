<?php
// FROM HASH: 50e38cee484e99d8b2c719482d33b505
return array(
'macros' => array('navigation' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'pageSelected' => '!',
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
		), $__vars) . '
					</div>
				</div>
			</div>

		', 'replace');
		$__finalCompiled .= '
		';
		$__templater->setPageParam('sideNavTitle', 'Brand list');
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
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<a class="' . $__templater->escape($__vars['baseClass']) . ' ' . (($__vars['pageSelected'] == 'all') ? $__templater->escape($__vars['selectedClass']) : '') . '"
		href="' . $__templater->func('link', array('bh_brands', ), true) . '" rel="nofollow">' . 'All' . '</a>
	<a class="' . $__templater->escape($__vars['baseClass']) . ' ' . (($__vars['pageSelected'] == 'view_count') ? $__templater->escape($__vars['selectedClass']) : '') . '"
		href="' . $__templater->func('link', array('bh_brands&type=view_count', ), true) . '" rel="nofollow">' . 'Most Viewed' . '</a>
	<a class="' . $__templater->escape($__vars['baseClass']) . ' ' . (($__vars['pageSelected'] == 'discussion_count') ? $__templater->escape($__vars['selectedClass']) : '') . '"
		href="' . $__templater->func('link', array('bh_brands&type=discussion_count', ), true) . '" rel="nofollow">' . 'Most Discussed' . '</a>
	<a class="' . $__templater->escape($__vars['baseClass']) . ' ' . (($__vars['pageSelected'] == 'rating_avg') ? $__templater->escape($__vars['selectedClass']) : '') . '"
		href="' . $__templater->func('link', array('bh_brands&type=rating_avg', ), true) . '" rel="nofollow">' . 'Highest Rated' . '</a>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Select a Brand');
	$__finalCompiled .= '

';
	$__templater->includeCss('bh_brandHub_list.less');
	$__finalCompiled .= '


		
' . $__templater->callMacro(null, 'navigation', array(
		'pageSelected' => $__vars['pageSelected'],
	), $__vars) . '

<div class="block-container">
	<div class="block-body">
		';
	if (!$__templater->test($__vars['brands'], 'empty', array())) {
		$__finalCompiled .= '
			
		';
	} else {
		$__finalCompiled .= '
			<div class="blockMessage">' . 'No results found.' . '</div>
		';
	}
	$__finalCompiled .= '
		
		<div>' . 'Select a Brand' . '</div>
		
		
		';
	$__templater->modifySidebarHtml('infoSidebar', '
			<div class="block">
				<div class="block-container">
					
					<br><br><br><br><br><br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	[ custom fields]
					<br><br><br><br><br><br>
				</div>
			</div>
		', 'replace');
	$__finalCompiled .= '
		
	</div>
</div>






' . '

';
	return $__finalCompiled;
}
);