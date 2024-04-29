<?php
// FROM HASH: 62abd6cf86db06e6b3c74bb7449e3103
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['item']['Brand']['brand_title']) . ' ' . $__templater->escape($__vars['item']['item_title']));
	$__finalCompiled .= '
';
	$__templater->breadcrumbs($__templater->method($__vars['page'], 'getBreadcrumbs', array(false, )));
	$__finalCompiled .= '

';
	$__templater->includeCss('bh_brandHub_list.less');
	$__finalCompiled .= '
<div class="block">
	<div class="block-container">
		<div class="block-header">
			<h3 class="block-minorHeader">' . '' . $__templater->escape($__vars['item']['Brand']['brand_title']) . ' ' . $__templater->escape($__vars['item']['item_title']) . ' Owner Pages' . '</h3>
			<div class="p-description">' . 'View owner pages to see photos, customization and comments  about member-owner ' . $__templater->escape($__vars['item']['Brand']['brand_title']) . ' ' . $__templater->escape($__vars['item']['item_title']) . 's' . '</div>
		</div>
		<div class="block-body block-row block-row--separated">
			<div class="block-body">
				<div class="">
					<div style="float:left;">
						' . $__templater->callMacro('bh_nav_for_ownerPage', 'navigation', array(
		'pageSelected' => $__vars['pageSelected'],
		'total' => $__vars['ownerPageTotal'],
		'route' => 'bh_item/ownerpage',
		'brand' => $__vars['item'],
	), $__vars) . '
					</div>
					<div style="float:right;">
						' . $__templater->button('Create ' . $__templater->escape($__vars['item']['item_title']) . ' owner page', array(
		'href' => $__templater->func('link', array('bh_item/ownerpage/add', $__vars['item'], array('item' => $__vars['item']['item_id'], ), ), false),
		'class' => 'button--fullWidth',
		'overlay' => 'true',
	), '', array(
	)) . '
					</div>
				</div>


			<div class="clearfix"></div>


			<div class="block-body">
				';
	if (!$__templater->test($__vars['itemPages'], 'empty', array())) {
		$__finalCompiled .= '
						<div class="brandHub">		
							<ul class="grid-page-all">
								';
		if ($__templater->isTraversable($__vars['itemPages'])) {
			foreach ($__vars['itemPages'] AS $__vars['itemPage']) {
				$__finalCompiled .= '
								<li class="innerpage">
									<div class="borderpage">


												<a href="' . $__templater->func('link', array('bh_item/ownerpage/page', $__vars['itemPage'], ), true) . '" class="bh_a" >
													';
				if ($__templater->method($__vars['itemPage'], 'getthumbnailurl', array())) {
					$__finalCompiled .= '
														<img src="' . $__templater->escape($__templater->method($__vars['itemPage'], 'getthumbnailurl', array())) . '" class="pageimage" />	
													';
				} else {
					$__finalCompiled .= '
														<i class="fas fa-image fa-8x icon"  ></i><br>
													';
				}
				$__finalCompiled .= '	

													<strong>' . $__templater->escape($__vars['itemPage']['User']['username']) . ' ' . $__templater->escape($__vars['item']['Brand']['brand_title']) . ' ' . $__templater->escape($__vars['item']['item_title']) . ' </strong></a>


									</div>

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
		

				</div>
			</div>
		</div>
	</div>
</div>
<style>



</style>';
	return $__finalCompiled;
}
);