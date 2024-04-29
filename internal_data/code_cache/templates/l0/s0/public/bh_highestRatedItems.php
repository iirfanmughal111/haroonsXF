<?php
// FROM HASH: 9ae039a6308baf8f154c11dc964ee8e8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
		<div class="block-container">
				<h3 class="block-minorHeader">
					 ' . 'Highest Rated in This Category' . '
				</h3>
				<ul class="block-body">
					';
	if (!$__templater->test($__vars['highestRatedItems'], 'empty', array())) {
		$__finalCompiled .= '
						<div class="brandHub">		
							<ul class="highestRated_grid-list">
								';
		if ($__templater->isTraversable($__vars['highestRatedItems'])) {
			foreach ($__vars['highestRatedItems'] AS $__vars['highestRatedItem']) {
				$__finalCompiled .= '
								
								<li class="bh_item">

									<div class="contentRow-main contentRow-main--close">
										';
				if ($__templater->method($__vars['highestRatedItem'], 'getthumbnailurl', array()) != '') {
					$__finalCompiled .= '
											   <a href="' . $__templater->func('link', array('bh_item/ownerpage', $__vars['highestRatedItem'], ), true) . '" class="bh_a" >
												<img src="' . $__templater->escape($__templater->method($__vars['highestRatedItem'], 'getthumbnailurl', array())) . '" alt="Item-image" />		

													<div class="contentRow-lesser">
														' . $__templater->escape($__vars['highestRatedItem']['item_title']) . '
														' . $__templater->callMacro('rating_macros', 'stars', array(
						'rating' => $__vars['highestRatedItem']['rating_avg'],
						'class' => 'ratingStars--smaller',
					), $__vars) . ' (' . $__templater->escape($__vars['highestRatedItem']['rating_count']) . ') 
													</div>
												</a>
										';
				} else {
					$__finalCompiled .= '
											   <a href="' . $__templater->func('link', array('bh_item/ownerpage', $__vars['highestRatedItem'], ), true) . '" class="bh_a" >
												   <i class="fas fa-image fa-4x" ></i>
												<div class="contentRow-lesser">
														' . $__templater->escape($__vars['highestRatedItem']['item_title']) . '
														' . $__templater->callMacro('rating_macros', 'stars', array(
						'rating' => $__vars['highestRatedItem']['rating_avg'],
						'class' => 'ratingStars--smaller',
					), $__vars) . ' (' . $__templater->escape($__vars['highestRatedItem']['rating_count']) . ') 
													</div>
												</a>
										';
				}
				$__finalCompiled .= '
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
				</ul>
		</div>
	</div>';
	return $__finalCompiled;
}
);