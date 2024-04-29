<?php
// FROM HASH: 0b8fe5d5f78cbc046b1446bfd96ba243
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Media Tag List');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add Media Tag', array(
		'href' => $__templater->func('link', array('media-tag/add', ), false),
		'icon' => 'add',
		'overlay' => 'true',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

<div class="block-container">
		';
	if (!$__templater->test($__vars['mediaTags'], 'empty', array())) {
		$__finalCompiled .= '
			<div class="block-body">

				';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['mediaTags'])) {
			foreach ($__vars['mediaTags'] AS $__vars['mediaTag']) {
				$__compilerTemp1 .= '
						' . $__templater->dataRow(array(
				), array(array(
					'_type' => 'cell',
					'html' => $__templater->escape($__vars['mediaTag']['title']),
				),
				array(
					'href' => $__templater->func('link', array('media-tag/edit', $__vars['mediaTag'], ), false),
					'overlay' => 'true',
					'_type' => 'action',
					'html' => 'Edit',
				),
				array(
					'href' => $__templater->func('link', array('media-tag/delete', $__vars['mediaTag'], ), false),
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
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['mediaTags'], $__vars['total'], ), true) . '</span>
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
		'link' => 'media-tag',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer block-outer--after',
		'perPage' => $__vars['perPage'],
	)));
	return $__finalCompiled;
}
);