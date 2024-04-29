<?php
// FROM HASH: ee28a32b591f330f7d9b0921e9506187
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['brand'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add Brand');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit Brand' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['brand']['brand_title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['brand'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('bh_brand/delete', $__vars['brand'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	$__vars['forumSelection'] = $__templater->preEscaped('
	' . $__templater->callMacro('bh_edit_macros', 'select_forums', array(
		'nodeIds' => $__vars['brand']['node_ids'],
		'nodeTree' => $__vars['nodeTree'],
	), $__vars) . '
');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['brand']['Description']) {
		$__compilerTemp1 .= '
				' . $__templater->formHiddenVal('descriptionId', $__vars['brand']['Description']['desc_id'], array(
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			
			' . $__templater->formTextBoxRow(array(
		'name' => 'brand_title',
		'value' => $__vars['brand']['brand_title'],
		'maxlength' => $__templater->func('max_length', array($__vars['brand'], 'brand_title', ), false),
		'required' => 'true',
		'autofocus' => 'true',
	), array(
		'label' => 'Title',
	)) . '
			
			' . $__templater->formEditorRow(array(
		'name' => 'description',
		'value' => $__vars['brand']['Description']['description'],
		'data-min-height' => '200',
	), array(
		'label' => 'Description',
	)) . '

			' . $__compilerTemp1 . '
			
			
			' . $__templater->filter($__vars['forumSelection'], array(array('raw', array()),), true) . '
			
			' . $__templater->formTextBoxRow(array(
		'name' => 'forums_link',
		'value' => $__vars['brand']['forums_link'],
		'maxlength' => $__templater->func('max_length', array($__vars['brand'], 'forums_link', ), false),
		'placeholder' => 'Enter URL',
	), array(
		'label' => 'Forums link',
	)) . '
			
			' . $__templater->formTextBoxRow(array(
		'name' => 'website_link',
		'value' => $__vars['brand']['website_link'],
		'maxlength' => $__templater->func('max_length', array($__vars['brand'], 'website_link', ), false),
		'placeholder' => 'Enter URL',
	), array(
		'label' => 'Website link',
	)) . '
			
			' . $__templater->formTextBoxRow(array(
		'name' => 'for_sale_link',
		'value' => $__vars['brand']['for_sale_link'],
		'maxlength' => $__templater->func('max_length', array($__vars['brand'], 'for_sale_link', ), false),
		'placeholder' => 'Enter URL',
	), array(
		'label' => 'Used for sale link',
	)) . '
			
			' . $__templater->formTextBoxRow(array(
		'name' => 'intro_link',
		'value' => $__vars['brand']['intro_link'],
		'maxlength' => $__templater->func('max_length', array($__vars['brand'], 'intro_link', ), false),
		'placeholder' => 'Enter URL',
	), array(
		'label' => 'Introduction link',
	)) . '

		</div>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('bh_brand/save', $__vars['brand'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);