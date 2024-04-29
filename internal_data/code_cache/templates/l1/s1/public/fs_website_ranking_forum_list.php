<?php
// FROM HASH: f27107e95704e24f386c6ee51de5c9a0
return array(
'macros' => array('node_list_entry' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'node' => '!',
		'extras' => '!',
		'children' => '!',
		'childExtras' => '!',
		'depth' => '1',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('node_list.less');
	$__finalCompiled .= '
	';
	$__vars['nodeTemplate'] = $__templater->method($__vars['node'], 'getNodeTemplateRenderer', array($__vars['depth'], ));
	$__finalCompiled .= '
	
	';
	if ($__vars['nodeTemplate']['macro']) {
		$__finalCompiled .= '
		' . $__templater->callMacro($__vars['nodeTemplate']['template'], $__vars['nodeTemplate']['macro'], array(
			'node' => $__vars['node'],
			'extras' => $__vars['extras'],
			'children' => $__vars['children'],
			'childExtras' => $__vars['childExtras'],
			'depth' => $__vars['depth'],
		), $__vars) . '
	';
	} else if ($__vars['nodeTemplate']['template']) {
		$__finalCompiled .= '
		' . $__templater->func('dump', array('elseif', ), true) . '
		' . $__templater->includeTemplate($__vars['nodeTemplate']['template'], $__vars) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'node_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'children' => '!',
		'extras' => '!',
		'depth' => '1',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('node_list.less');
	$__finalCompiled .= '

	';
	if ($__templater->isTraversable($__vars['children'])) {
		foreach ($__vars['children'] AS $__vars['id'] => $__vars['child']) {
			$__finalCompiled .= '
		' . $__templater->callMacro(null, 'node_list_entry', array(
				'node' => $__vars['child']['record'],
				'extras' => $__vars['extras'][$__vars['id']],
				'children' => $__vars['child']['children'],
				'childExtras' => $__vars['extras'],
				'depth' => $__vars['depth'],
			), $__vars) . '
	';
		}
	}
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
),
'sub_node_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'children' => '!',
		'childExtras' => '!',
		'depth' => '3',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
			' . $__templater->callMacro('forum_list', 'node_list', array(
		'children' => $__vars['children'],
		'extras' => $__vars['childExtras'],
		'depth' => ($__vars['depth'] + 1),
	), $__vars) . '
		';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<ol>
		' . $__compilerTemp1 . '
		</ol>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'sub_nodes_flat' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'children' => '!',
		'childExtras' => '!',
		'depth' => '3',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('node_list.less');
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
				' . $__templater->callMacro('forum_list', 'node_list', array(
		'children' => $__vars['children'],
		'extras' => $__vars['childExtras'],
		'depth' => ($__vars['depth'] + 1),
	), $__vars) . '
			';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="node-subNodesFlat">
			<span class="node-subNodesLabel">' . 'Sub-forums' . $__vars['xf']['language']['label_separator'] . '</span>
			<ol class="node-subNodeFlatList">
			' . $__compilerTemp1 . '
			</ol>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'sub_nodes_menu' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'children' => '!',
		'childExtras' => '!',
		'depth' => '3',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('node_list.less');
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
						' . $__templater->callMacro('forum_list', 'node_list', array(
		'children' => $__vars['children'],
		'extras' => $__vars['childExtras'],
		'depth' => ($__vars['depth'] + 1),
	), $__vars) . '
					';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="node-subNodeMenu">
			<a class="menuTrigger" data-xf-click="menu" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true">' . 'Sub-forums' . '</a>
			<div class="menu" data-menu="menu" aria-hidden="true">
				<div class="menu-content">
					<h4 class="menu-header">' . 'Sub-forums' . '</h4>
					<ol class="subNodeMenu">
					' . $__compilerTemp1 . '
					</ol>
				</div>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'search_menu' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'conditions' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="block-filterBar">
		<div class="filterBar">
			<a
			   class="filterBar-menuTrigger"
			   data-xf-click="menu"
			   role="button"
			   tabindex="0"
			   aria-expanded="false"
			   aria-haspopup="true"
			   >' . 'Filters' . '</a
				>
			<div
				 class="menu menu--wide"
				 data-menu="menu"
				 aria-hidden="true"
				 data-href="' . $__templater->func('link', array('web-ranking/refine-search', null, $__vars['conditions'], ), true) . '"
				 data-load-target=".js-filterMenuBody"
				 >
				<div class="menu-content">
					<h4 class="menu-header">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</h4>
					<div class="js-filterMenuBody">
						<div class="menu-row">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
					</div>
				</div>
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Website Ranking');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	if ($__vars['xf']['options']['fs_web_ranking_parent_web_id'] != 0) {
		$__finalCompiled .= '
	' . $__templater->includeTemplate('fs_wesite_ranking_stats', $__vars) . '
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'description' => $__vars['xf']['options']['boardDescription'],
		'canonicalUrl' => $__templater->func('link', array('canonical:' . $__vars['selfRoute'], ), false),
	), $__vars) . '


' . $__templater->callMacro(null, 'search_menu', array(
		'conditions' => $__vars['filters'],
	), $__vars) . '
' . $__templater->callMacro(null, 'node_list', array(
		'children' => $__vars['nodeTree'],
		'extras' => $__vars['nodeExtras'],
	), $__vars) . '


';
	$__templater->setPageParam('head.' . 'rss_forum', $__templater->preEscaped('<link rel="alternate" type="application/rss+xml" title="' . $__templater->filter('RSS feed for ' . $__vars['xf']['options']['boardTitle'] . '', array(array('for_attr', array()),), true) . '" href="' . $__templater->func('link', array('forums/index.rss', '-', ), true) . '" />'));
	$__finalCompiled .= '

' . '

' . '

' . '

' . '

' . '


<!-- Filter Bar Macro Start -->

';
	return $__finalCompiled;
}
);