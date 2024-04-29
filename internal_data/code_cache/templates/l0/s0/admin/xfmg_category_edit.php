<?php
// FROM HASH: c64346284ac0ce7b86afb1a0b977184d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['category'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add category');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit category' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['category']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__vars['category']['category_id']) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('media-gallery/categories/delete', $__vars['category'], ), false),
			'icon' => 'delete',
			'data-xf-click' => 'overlay',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['category'], 'isEmpty', array())) {
		$__compilerTemp1 .= '
				';
		$__compilerTemp2 = $__templater->mergeChoiceOptions(array(), $__vars['categoryTypes']);
		$__compilerTemp1 .= $__templater->formRadioRow(array(
			'name' => 'category_type',
			'value' => $__vars['category']['category_type'],
		), $__compilerTemp2, array(
			'label' => 'Category type',
			'explain' => 'Container categories are special categories that cannot directly contain albums or media items, but instead will display items from their child categories.',
		)) . '
			';
	} else {
		$__compilerTemp1 .= '
				' . $__templater->formRow('
					' . $__templater->escape($__vars['categoryTypes'][$__vars['category']['category_type']]) . '
					<div class="formRow-explain">' . 'The category type can only be changed when the category is empty.' . '</div>
					' . $__templater->formHiddenVal('category_type', $__vars['category']['category_type'], array(
		)) . '
				', array(
			'label' => 'Category type',
		)) . '
			';
	}
	$__compilerTemp3 = $__templater->mergeChoiceOptions(array(), $__vars['mediaTypes']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['category']['title'],
	), array(
		'label' => 'Title',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'description',
		'value' => $__vars['category']['description'],
		'rows' => '2',
		'autosize' => 'true',
	), array(
		'label' => 'Description',
		'hint' => 'You may use HTML',
	)) . '

			' . $__templater->callMacro('category_tree_macros', 'parent_category_select_row', array(
		'category' => $__vars['category'],
		'categoryTree' => $__vars['categoryTree'],
	), $__vars) . '

			' . $__templater->callMacro('display_order_macros', 'row', array(
		'value' => $__vars['category']['display_order'],
	), $__vars) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'min_tags',
		'value' => $__vars['category']['min_tags'],
		'min' => '0',
		'max' => '100',
	), array(
		'label' => 'Minimum required tags',
		'explain' => 'This allows you to require users to enter at least this many tags when adding media or editing tags on existing media.',
	)) . '

			<hr class="formRowSep" />

			' . $__compilerTemp1 . '

			' . $__templater->formCheckBoxRow(array(
		'name' => 'allowed_types',
		'listclass' => 'listColumns',
		'value' => $__vars['category']['allowed_types'],
	), $__compilerTemp3, array(
		'label' => 'Allowed media types',
		'explain' => 'The types selected above are only relevant to album and media categories.',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('media-gallery/categories/save', $__vars['category'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);