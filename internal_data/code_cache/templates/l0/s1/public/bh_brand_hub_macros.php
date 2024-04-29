<?php
// FROM HASH: 00f8ebd2c1d3308bd1d32675f701d17b
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
	
	<a class="' . $__templater->escape($__vars['baseClass']) . ' ' . (($__vars['pageSelected'] == 'rating_avg') ? $__templater->escape($__vars['selectedClass']) : '') . '"
		href="' . $__templater->func('link', array($__vars['route'], $__vars['brand'], array('type' => 'rating_avg', ), ), true) . '" rel="nofollow">' . 'Highest Rated' . '</a>
';
	return $__finalCompiled;
}
),
'brandRelatedLinks' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'brandObj' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ((($__vars['brandObj']['forums_link'] OR $__vars['brandObj']['website_link']) OR $__vars['brandObj']['for_sale_link']) OR $__vars['brandObj']['intro_link']) {
		$__finalCompiled .= '
	<div class="block">
				<div class="block-container">
					';
		$__compilerTemp1 = '';
		$__compilerTemp1 .= '
									<div class="block-body">
										';
		$__compilerTemp2 = '';
		if ($__vars['brandObj']['forums_link']) {
			$__compilerTemp2 .= '
												' . $__templater->dataRow(array(
				'if' => '$brandObj.forums_link',
			), array(array(
				'href' => $__vars['brandObj']['forums_link'],
				'target' => '_blank',
				'_type' => 'cell',
				'html' => '' . $__templater->escape($__vars['brandObj']['brand_title']) . ' Forums',
			))) . '
											';
		}
		$__compilerTemp3 = '';
		if ($__vars['brandObj']['website_link']) {
			$__compilerTemp3 .= '
												' . $__templater->dataRow(array(
			), array(array(
				'href' => $__vars['brandObj']['website_link'],
				'target' => '_blank',
				'_type' => 'cell',
				'html' => '' . $__templater->escape($__vars['brandObj']['brand_title']) . ' Website',
			))) . '
											';
		}
		$__compilerTemp4 = '';
		if ($__vars['brandObj']['for_sale_link']) {
			$__compilerTemp4 .= '
												' . $__templater->dataRow(array(
			), array(array(
				'href' => $__vars['brandObj']['for_sale_link'],
				'target' => '_blank',
				'_type' => 'cell',
				'html' => 'Used ' . $__templater->escape($__vars['brandObj']['brand_title']) . ' for sale',
			))) . '
											';
		}
		$__compilerTemp5 = '';
		if ($__vars['brandObj']['intro_link']) {
			$__compilerTemp5 .= '
												' . $__templater->dataRow(array(
			), array(array(
				'href' => $__vars['brandObj']['intro_link'],
				'target' => '_blank',
				'_type' => 'cell',
				'html' => 'Introduction to ' . $__templater->escape($__vars['brandObj']['brand_title']) . '',
			))) . '
											';
		}
		$__compilerTemp1 .= $__templater->dataList('

											' . $__compilerTemp2 . '
											' . $__compilerTemp3 . '
											' . $__compilerTemp4 . '
											' . $__compilerTemp5 . '
										', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
									</div>
								';
		if (strlen(trim($__compilerTemp1)) > 0) {
			$__finalCompiled .= '
						<h3 class="block-minorHeader">' . ($__vars['brandObj']['brand_title'] ? 'Related ' . $__templater->escape($__vars['brandObj']['brand_title']) . ' Links' : 'Related Links') . '</h3>
		
							<div class="block-body block-row block-row--separated">
								' . $__compilerTemp1 . '
							</div>
					';
		}
		$__finalCompiled .= '
				</div>
			</div>
';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '



';
	return $__finalCompiled;
}
);