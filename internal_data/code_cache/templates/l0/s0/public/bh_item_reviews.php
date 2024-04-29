<?php
// FROM HASH: 05076c2584474c937f0351662762cfc6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['item']['Brand']['brand_title']) . ' ' . $__templater->escape($__vars['item']['item_title']));
	$__finalCompiled .= '
';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__templater->includeCss('bh_brandHub_list.less');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-header">
			<h3 class="block-minorHeader">' . 'User Reviews' . ' (' . $__templater->filter($__vars['item']['rating_count'], array(array('number', array()),), true) . ')</h3>
			<div class="p-description">' . 'Read what TractorByNet members think about the ' . $__templater->escape($__vars['item']['Brand']['brand_title']) . ' ' . $__templater->escape($__vars['item']['item_title']) . ' Subcompact Tractor' . '</div>
		</div>
		<div class="block-body block-row block-row--separated">
			<div class="block-body">
				
		';
	if (!$__templater->test($__vars['itemReviews'], 'empty', array())) {
		$__finalCompiled .= '
					';
		if ($__templater->isTraversable($__vars['itemReviews'])) {
			foreach ($__vars['itemReviews'] AS $__vars['review']) {
				$__finalCompiled .= '
						' . $__templater->callMacro('bh_item_review_macros', 'review_simple', array(
					'review' => $__vars['review'],
				), $__vars) . '
						<br>
					';
			}
		}
		$__finalCompiled .= '
			
			<div class="block-footer">
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['itemReviews'], $__vars['total'], ), true) . '</span>
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
	' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'bh_brands/item/' . $__vars['item']['item_id'] . '/reviews/',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer block-outer--after',
		'perPage' => $__vars['perPage'],
	))) . '
</div>';
	return $__finalCompiled;
}
);