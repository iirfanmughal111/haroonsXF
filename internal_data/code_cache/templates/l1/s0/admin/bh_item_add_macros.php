<?php
// FROM HASH: 0cf0a794e4435f1abbd85551bfbb893a
return array(
'macros' => array('add_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'attachmentData' => '!',
		'item' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeJs(array(
		'prod' => 'xf/attachment_manager-compiled.js',
		'dev' => 'vendor/flow.js/flow-compiled.js, xf/attachment_manager.js',
	));
	$__finalCompiled .= '

';
	if ($__vars['item']) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('item-list/delete', $__vars['item'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '
	
';
	if ($__vars['item']) {
		$__finalCompiled .= '
		';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['item']['item_title']));
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
  		';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add item');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '
	
';
	$__compilerTemp1 = array(array(
		'value' => '',
		'selected' => !$__vars['nodeIds'],
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['brandCategories'])) {
		foreach ($__vars['brandCategories'] AS $__vars['brandCategory']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['brandCategory']['category_id'],
				'label' => $__templater->escape($__vars['brandCategory']['category_title']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('

	<div class="block-container">
		<div class="block-body">
			
		' . $__templater->formTextBoxRow(array(
		'name' => 'item_title',
		'value' => $__vars['item']['item_title'],
		'maxlength' => $__templater->func('max_length', array($__vars['item'], 'brand_title', ), false),
		'required' => 'true',
		'autofocus' => 'true',
	), array(
		'label' => 'Title',
	)) . '
			
	  ' . $__templater->formTextBoxRow(array(
		'name' => 'make',
		'value' => $__vars['item']['make'],
		'maxlength' => $__templater->func('max_length', array($__vars['item'], 'make', ), false),
		'required' => 'true',
		'autofocus' => 'true',
	), array(
		'label' => 'Make',
	)) . '
			
	' . $__templater->formTextBoxRow(array(
		'name' => 'model',
		'value' => $__vars['item']['model'],
		'maxlength' => $__templater->func('max_length', array($__vars['item'], 'model', ), false),
		'required' => 'true',
		'autofocus' => 'true',
	), array(
		'label' => 'Model',
	)) . '
			

	' . $__templater->formSelectRow(array(
		'name' => 'category_id',
		'value' => $__vars['brand']['category_id'],
	), $__compilerTemp1, array(
		'label' => 'Category',
	)) . '
			
		<div class="js-inlineNewPostFields">
			
		' . $__templater->formEditorRow(array(
		'name' => 'description',
		'value' => $__vars['description'],
	), array(
		'label' => 'Description',
	)) . '
		
			
	' . $__templater->formRow('
		' . $__templater->callMacro('helper_attachment_item', 'upload_block', array(
		'attachmentData' => $__vars['attachmentData'],
	), $__vars) . '
			', array(
	)) . '
			
		
			
			' . $__templater->callMacro('item_custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'bhItemfield',
		'set' => $__vars['item']['custom_fields'],
		'namePrefix' => 'item[custom_fields]',
		'rowType' => 'noGutter',
		'rowClass' => 'mediaItem-input',
		'onlyInclude' => $__vars['category']['field_cache'],
	), $__vars) . '
			
		
			</div>
				' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
			</div>
		</div>

', array(
		'action' => $__templater->func('link', array('item-list/create', $__vars['item'], ), false),
		'ajax' => 'true',
		'data-xf-init' => 'attachment-manager',
	)) . '
	';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);