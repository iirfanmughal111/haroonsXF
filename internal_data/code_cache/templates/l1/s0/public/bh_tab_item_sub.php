<?php
// FROM HASH: 52a5339dff7c52777b1f4caeb0f4faa8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block">
	<div class="block-container">
		<div class="block-header">
			<h3 class="block-minorHeader">' . 'Subscribe Items' . '</h3>
		</div>
		<div class="block-body block-row block-row--separated">
			<div class="block-body">

			<div class="block-body">
				';
	if (!$__templater->test($__vars['sublist'], 'empty', array())) {
		$__finalCompiled .= '
		            ';
		$__compilerTemp1 = array(array(
			'_type' => 'cell',
			'html' => 'Title',
		)
,array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => '&nbsp;',
		)
,array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => '&nbsp;',
		)
,array(
			'_type' => 'cell',
			'html' => 'Subcribe Date',
		));
		if ($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) {
			$__compilerTemp1[] = array(
				'_type' => 'cell',
				'html' => 'Action',
			);
		}
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['sublist'])) {
			foreach ($__vars['sublist'] AS $__vars['sub']) {
				$__compilerTemp2 .= '
						';
				$__compilerTemp3 = array(array(
					'_type' => 'cell',
					'html' => $__templater->escape($__vars['sub']['Item']['item_title']),
				)
,array(
					'class' => 'dataList-cell--min',
					'_type' => 'cell',
					'html' => '&nbsp;',
				)
,array(
					'class' => 'dataList-cell--min',
					'_type' => 'cell',
					'html' => '&nbsp;',
				)
,array(
					'_type' => 'cell',
					'html' => $__templater->func('date_dynamic', array($__vars['sub']['create_date'], array(
				))),
				));
				if ($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) {
					$__compilerTemp3[] = array(
						'href' => $__templater->func('link', array('bh_brands/item/unsub', $__vars['sub'], ), false),
						'overlay' => 'true',
						'_type' => 'action',
						'html' => 'UnSubcribe',
					);
				}
				$__compilerTemp2 .= $__templater->dataRow(array(
				), $__compilerTemp3) . '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), $__compilerTemp1) . '
					' . $__compilerTemp2 . '
				', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
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