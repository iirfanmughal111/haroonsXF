<?php
// FROM HASH: 232eaad5e5bc1cd4832d4703f7c914d5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Brands');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add Brand', array(
		'href' => $__templater->func('link', array('bh_brand/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '
	
	<div class="block-container">
		';
	if (!$__templater->test($__vars['brands'], 'empty', array())) {
		$__finalCompiled .= '
			<div class="block-body">
				
				';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['brands'])) {
			foreach ($__vars['brands'] AS $__vars['brand']) {
				$__compilerTemp1 .= '
						' . $__templater->dataRow(array(
				), array(array(
					'_type' => 'cell',
					'html' => $__templater->escape($__vars['brand']['brand_title']),
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->escape($__vars['brand']['discussion_count']),
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->escape($__vars['brand']['view_count']),
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->escape($__vars['brand']['rating_count']),
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->escape($__vars['brand']['rating_avg']),
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->escape($__vars['brand']['review_count']),
				),
				array(
					'href' => $__templater->func('link', array('bh_brand/edit', $__vars['brand'], ), false),
					'_type' => 'action',
					'html' => 'Edit',
				),
				array(
					'href' => $__templater->func('link', array('bh_brand/delete', $__vars['brand'], ), false),
					'_type' => 'delete',
					'html' => '',
				))) . '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), array(array(
			'_type' => 'cell',
			'html' => 'Title',
		),
		array(
			'_type' => 'cell',
			'html' => 'Discussions',
		),
		array(
			'_type' => 'cell',
			'html' => 'Views',
		),
		array(
			'_type' => 'cell',
			'html' => 'Ratings',
		),
		array(
			'_type' => 'cell',
			'html' => 'Rating average',
		),
		array(
			'_type' => 'cell',
			'html' => 'Reviews',
		),
		array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => '&nbsp;',
		),
		array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => '&nbsp;',
		))) . '
					' . $__compilerTemp1 . '
				', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
			</div>
			<div class="block-footer">
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['brands'], $__vars['total'], ), true) . '</span>
			</div>
		';
	} else {
		$__finalCompiled .= '
			<div class="block-body block-row">' . 'No results found.' . '</div>
		';
	}
	$__finalCompiled .= '
	</div>

	' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'bh_brand',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer block-outer--after',
		'perPage' => $__vars['perPage'],
	)));
	return $__finalCompiled;
}
);