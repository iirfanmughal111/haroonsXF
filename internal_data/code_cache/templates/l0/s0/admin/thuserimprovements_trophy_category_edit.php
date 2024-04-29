<?php
// FROM HASH: d55e7958d88dc25c3e8f7053dce9749c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['category'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add trophy category');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit trophy category' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['category']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['category'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('trophies/thui-category/delete', $__vars['category'], ), false),
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
		'name' => 'trophy_category_id',
		'value' => $__vars['category']['trophy_category_id'],
		'maxlength' => $__templater->func('max_length', array($__vars['category'], 'trophy_category_id', ), false),
		'dir' => 'ltr',
	), array(
		'label' => 'Trophy category ID',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => ($__templater->method($__vars['category'], 'exists', array()) ? $__vars['category']['MasterTitle']['phrase_text'] : ''),
	), array(
		'label' => 'Title',
	)) . '

			' . $__templater->callMacro('display_order_macros', 'row', array(
		'value' => $__vars['category']['display_order'],
	), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>

', array(
		'action' => $__templater->func('link', array('trophies/thui-category/save', $__vars['category'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);