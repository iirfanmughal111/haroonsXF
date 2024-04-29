<?php
// FROM HASH: 655a836c3eea6e80c9d7f1c208a670c9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Videos by Ron');
	$__finalCompiled .= '
	
';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add Ron Video', array(
		'href' => $__templater->func('link', array('by-rons/add', ), false),
		'icon' => 'add',
		'overlay' => 'true',
	), '', array(
	)) . '
	' . $__templater->button('<i class="far fa-image"></i>  ' . ($__vars['ronLogo'] ? 'Update Logo' : 'Add Logo'), array(
		'href' => $__templater->func('link', array('by-rons/upload-logo', ), false),
		'overlay' => 'true',
	), '', array(
	)) . '
	
	<img src="' . $__templater->func('base_url', array('data/brand_img/logo_images/ron-logo.jpg?t=', ), true) . $__templater->escape($__vars['xf']['time']) . '" width="60" height="50" alt="Ron-page-logo-image" />
');
	$__finalCompiled .= '


	<div class="block-container">
		';
	if (!$__templater->test($__vars['rons'], 'empty', array())) {
		$__finalCompiled .= '
			<div class="block-body">
				';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['rons'])) {
			foreach ($__vars['rons'] AS $__vars['ron']) {
				$__compilerTemp1 .= '
						' . $__templater->dataRow(array(
				), array(array(
					'_type' => 'cell',
					'html' => $__templater->escape($__vars['ron']['title']),
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->escape($__vars['ron']['video_url']),
				),
				array(
					'href' => $__templater->func('link', array('by-rons/edit', $__vars['ron'], ), false),
					'overlay' => 'true',
					'_type' => 'action',
					'html' => 'Edit',
				),
				array(
					'href' => $__templater->func('link', array('by-rons/delete', $__vars['ron'], ), false),
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
			'html' => 'URL',
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
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['rons'], $__vars['total'], ), true) . '</span>
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
		'link' => 'by-rons',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer block-outer--after',
		'perPage' => $__vars['perPage'],
	)));
	return $__finalCompiled;
}
);