<?php
// FROM HASH: 49c0af38ad07a8e636611869ee66c4df
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Node Url All Records');
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped('Home'), '#', array(
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
  ' . $__templater->button('Add Url', array(
		'href' => $__templater->func('link', array('nodeUrl/add-view', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['data'], 'empty', array())) {
		$__finalCompiled .= '
  <div class="block">
    <div class="block-outer">
      ' . $__templater->callMacro('node_url_filter', 'quick_filter', array(
			'key' => 'nodeUrl',
			'class' => 'block-outer-opposite',
		), $__vars) . '
    </div>
    <div class="block-container">
      <div class="block-body">
        <!--       < Records >  -->

        ';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['data'])) {
			foreach ($__vars['data'] AS $__vars['val']) {
				$__compilerTemp1 .= '
            ' . $__templater->dataRow(array(
				), array(array(
					'_type' => 'cell',
					'html' => ' ' . $__templater->escape($__vars['val']['title']) . ' ',
				),
				array(
					'_type' => 'cell',
					'html' => ' ' . $__templater->escape($__vars['val']['node_url']) . ' ',
				),
				array(
					'href' => $__templater->func('link', array('nodeUrl/edit-view', $__vars['val'], ), false),
					'overlay' => 'true',
					'_type' => 'action',
					'html' => 'Edit',
				),
				array(
					'href' => $__templater->func('link', array('nodeUrl/delete-record', $__vars['val'], ), false),
					'overlay' => 'true',
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
			'html' => ' ' . 'Fourm' . ' ',
		),
		array(
			'_type' => 'cell',
			'html' => ' Node Url ',
		),
		array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => '  ',
		),
		array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => '  ',
		))) . '
          ' . $__compilerTemp1 . '
        ', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
        ' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'nodeUrl',
			'wrapperclass' => 'block',
			'perPage' => $__vars['perPage'],
		))) . '
        <!--       </ Records > -->
      </div>

      <!-- <div class="block-footer">
        <span class="block-footer-counter">footer</span>
      </div> -->
    </div>
  </div>
  ';
	} else {
		$__finalCompiled .= '
  <div class="blockMessage">' . 'No items have been created yet.' . '</div>
';
	}
	return $__finalCompiled;
}
);