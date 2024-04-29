<?php
// FROM HASH: c716060d2c2c067f5e7b093e87041e8c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Security Question All Records');
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped('Security Question All Records'), '#', array(
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
  ' . $__templater->button('Add Question', array(
		'href' => $__templater->func('link', array('securityQuestion/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['question'], 'empty', array())) {
		$__finalCompiled .= '
  <div class="block">
    <div class="block-outer">
      ' . $__templater->callMacro('security_question_filter', 'quick_filter', array(
			'key' => 'securityQuestion',
			'class' => 'block-outer-opposite',
		), $__vars) . '
    </div>
    <div class="block-container">
      <div class="block-body">
        <!--       < Records >  -->

        ';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['question'])) {
			foreach ($__vars['question'] AS $__vars['val']) {
				$__compilerTemp1 .= '
            ' . $__templater->dataRow(array(
				), array(array(
					'_type' => 'cell',
					'html' => ' ' . $__templater->escape($__vars['val']['security_question']) . ' ',
				),
				array(
					'href' => $__templater->func('link', array('securityQuestion/edit', $__vars['val'], ), false),
					'overlay' => 'true',
					'_type' => 'action',
					'html' => 'Edit',
				),
				array(
					'href' => $__templater->func('link', array('securityQuestion/delete-record', $__vars['val'], ), false),
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
			'html' => ' ' . 'Question' . ' ',
		),
		array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => ' ',
		),
		array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => ' ',
		))) . '
          ' . $__compilerTemp1 . '
        ', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
        ' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'securityQuestion',
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
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
);