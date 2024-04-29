<?php
// FROM HASH: cb0bfb59d981eaa605efd9908b67a1ec
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Select a Brand');
	$__finalCompiled .= '

';
	$__templater->includeCss('bh_brandHub_list.less');
	$__finalCompiled .= '


' . $__templater->callMacro('bh_brand_hub_macros', 'navigation', array(
		'pageSelected' => $__vars['pageSelected'],
		'total' => $__vars['total'],
		'route' => 'bh_brands',
	), $__vars) . '

<div class="block-container">
	<div class="block-body">
		';
	if (!$__templater->test($__vars['brands'], 'empty', array())) {
		$__finalCompiled .= '
				<div class="brandHub">		
					<ul class="grid-list">
						';
		if ($__templater->isTraversable($__vars['brands'])) {
			foreach ($__vars['brands'] AS $__vars['brand']) {
				$__finalCompiled .= '
							<li class="bh_item">
								<a href="' . $__templater->func('link', array('bh_brands/brand', $__vars['brand'], ), true) . '" class="bh_a" data-name="' . $__templater->escape($__vars['brand']['brand_title']) . '">' . $__templater->escape($__vars['brand']['brand_title']) . '</a>
							</li>
						';
			}
		}
		$__finalCompiled .= '
					</ul>	
				</div>
		';
	} else {
		$__finalCompiled .= '
			<div class="blockMessage">' . 'No results found.' . '</div>
		';
	}
	$__finalCompiled .= '
		
	' . $__templater->callMacro('bh_ad_macros', 'sideBar_brand', array(), $__vars) . '
		
	</div>
</div>';
	return $__finalCompiled;
}
);