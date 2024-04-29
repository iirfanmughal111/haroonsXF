<?php
// FROM HASH: 9a63577c4e2d47f49f4f08b4fb772de4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block" id="ownerPage">
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
							<ul class="grid-page-item">
								';
		if ($__templater->isTraversable($__vars['itemPages'])) {
			foreach ($__vars['itemPages'] AS $__vars['itemPage']) {
				$__finalCompiled .= '
								<li class="bh_item">
									<div class="borderpage">
                                             
										
												<a href="' . $__templater->func('link', array('bh_item/ownerpage/page', $__vars['itemPage'], ), true) . '" class="bh_a" >
													';
				if ($__templater->method($__vars['itemPage'], 'getthumbnailurl', array()) != '') {
					$__finalCompiled .= '
														<img src="' . $__templater->escape($__templater->method($__vars['itemPage'], 'getthumbnailurl', array())) . '" class="pageimage" />	
													';
				} else {
					$__finalCompiled .= '
														<i class="fas fa-image fa-8x" ></i>
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
							';
		if ($__templater->func('count', array($__vars['itemPages'], ), false) == 10) {
			$__finalCompiled .= '
								<div style="text-align:center;">
										<a href="' . $__templater->func('link', array('bh_item/ownerpage', $__vars['item'], ), true) . '" target="_blank">' . 'Load more Owner Pages' . '</a>
								</div>
							';
		}
		$__finalCompiled .= '
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
</div>';
	return $__finalCompiled;
}
);