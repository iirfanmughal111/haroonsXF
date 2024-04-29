<?php
// FROM HASH: d64d19e21f841f969392d2089d3cc547
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['item'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add item');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit Item' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['item']['item_title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['item'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('bh_item/delete', $__vars['item'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '


';
	$__templater->includeJs(array(
		'prod' => 'xf/attachment_manager-compiled.js',
		'dev' => 'vendor/flow.js/flow-compiled.js, xf/attachment_manager.js',
	));
	$__finalCompiled .= '

';
	$__compilerTemp1 = array(array(
		'value' => '',
		'selected' => !$__vars['nodeIds'],
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['brands'])) {
		foreach ($__vars['brands'] AS $__vars['brand']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['brand']['brand_id'],
				'label' => $__templater->escape($__vars['brand']['brand_title']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp2 = array(array(
		'value' => '',
		'selected' => !$__vars['nodeIds'],
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['brandCategories'])) {
		foreach ($__vars['brandCategories'] AS $__vars['brandCategory']) {
			$__compilerTemp2[] = array(
				'value' => $__vars['brandCategory']['category_id'],
				'label' => $__templater->escape($__vars['brandCategory']['category_title']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('

	<div class="block-container">
		<div class="block-body">
			' . $__templater->formHiddenVal('attachment_time', $__vars['attachment_time'], array(
	)) . '
		' . $__templater->formTextBoxRow(array(
		'name' => 'item_title',
		'value' => $__vars['item']['item_title'],
		'maxlength' => $__templater->func('max_length', array($__vars['item'], 'brand_title', ), false),
		'required' => 'true',
		'autofocus' => 'true',
	), array(
		'label' => 'Title',
	)) . '	

			' . $__templater->formSelectRow(array(
		'name' => 'brand_id',
		'value' => $__vars['item']['brand_id'],
		'required' => 'true',
	), $__compilerTemp1, array(
		'label' => 'Make',
	)) . '
			
			' . $__templater->formSelectRow(array(
		'name' => 'category_id',
		'value' => $__vars['item']['category_id'],
		'required' => 'true',
	), $__compilerTemp2, array(
		'label' => 'Category',
	)) . '
			
		<div class="js-inlineNewPostFields">
			
		' . $__templater->formEditorRow(array(
		'name' => 'description',
		'value' => $__vars['item']['Description']['description'],
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
	</div>
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
			
	</div>

', array(
		'action' => $__templater->func('link', array('bh_item/save', $__vars['item'], ), false),
		'ajax' => 'true',
		'data-xf-init' => 'attachment-manager',
	));
	return $__finalCompiled;
}
);