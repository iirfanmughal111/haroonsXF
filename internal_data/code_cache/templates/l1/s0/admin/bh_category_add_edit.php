<?php
// FROM HASH: 266fce31f5bbad440b0b8c51d1854f2b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['brandCategory'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add Category');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit Category' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['brandCategory']['category_title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['brandCategory'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('bh_category/delete', $__vars['brandCategory'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			
			' . $__templater->formTextBoxRow(array(
		'name' => 'category_title',
		'value' => $__vars['brandCategory']['category_title'],
		'maxlength' => $__templater->func('max_length', array($__vars['brandCategory'], 'category_title', ), false),
		'required' => 'true',
		'autofocus' => 'true',
	), array(
		'label' => 'Title',
	)) . '

		</div>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('bh_category/save', $__vars['brandCategory'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);