<?php
// FROM HASH: 6eb24fe2b5350761fbcc9e98c2fd9dfd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['brandObj']['brand_title']) . ' ' . 'items');
	$__finalCompiled .= '

';
	$__templater->includeCss('bh_brandHub_list.less');
	$__finalCompiled .= '


' . $__templater->callMacro('bh_brand_hub_macros', 'navigation', array(
		'pageSelected' => $__vars['pageSelected'],
		'total' => $__vars['total'],
		'route' => 'bh_brands/brand',
		'brand' => $__vars['brandObj'],
	), $__vars) . '


<div class="block-container">
	<div class="block-body">
		';
	if (!$__templater->test($__vars['items'], 'empty', array())) {
		$__finalCompiled .= '
				<div class="brandHub">		
					<ul class="grid-list">
						';
		if ($__templater->isTraversable($__vars['items'])) {
			foreach ($__vars['items'] AS $__vars['item']) {
				$__finalCompiled .= '
							<li class="bh_item">
								<a href="' . $__templater->func('link', array('bh_brands/item', $__vars['item'], ), true) . '" class="bh_a" data-name="' . $__templater->escape($__vars['item']['item_title']) . '">' . $__templater->escape($__vars['item']['item_title']) . '</a>
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
		
		
		

			';
	$__templater->modifySidebarHtml('shareSidebar', '
				' . $__templater->callMacro('bh_brand_hub_macros', 'brandRelatedLinks', array(
		'brandObj' => $__vars['brandObj'],
	), $__vars) . '
			', 'replace');
	$__finalCompiled .= '
		
	' . $__templater->callMacro('bh_ad_macros', 'sideBar_itemlist', array(), $__vars) . '
		
	</div>
</div>

<div class=\'clearfix\'></div>
	<h4 class="block-body block-row block-row--separated">' . 'About ' . $__templater->escape($__vars['brandObj']['brand_title']) . '' . '</h4><br>

	<div class="block-container">	
		<blockquote class="message-body">
			' . $__templater->func('bb_code', array($__vars['brandObj']['Description']['description'], 'description', $__vars['brandObj']['Description'], ), true) . '
			<br>
			';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('bh_brand_hub', 'bh_can_edit_brandDescript', ))) {
		$__finalCompiled .= '
			<a href="' . $__templater->func('link', array('bh_brands/edit', $__vars['brandObj'], ), true) . '" data-xf-click="overlay">' . 'Edit' . '</a>
		';
	}
	$__finalCompiled .= '
		</blockquote>
		
	</div>';
	return $__finalCompiled;
}
);