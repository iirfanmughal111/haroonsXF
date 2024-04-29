<?php
// FROM HASH: 4a5695333fc0dced35c2050f944e74d6
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
	$__compilerTemp1 = '';
	if ($__vars['item']['Description']) {
		$__compilerTemp1 .= '
				' . $__templater->formHiddenVal('descriptionId', $__vars['item']['Description']['desc_id'], array(
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">

			
			' . $__templater->formEditorRow(array(
		'name' => 'description',
		'value' => $__vars['item']['Description']['description'],
		'data-min-height' => '200',
	), array(
		'label' => 'Description',
	)) . '


			' . $__compilerTemp1 . '
			
			' . $__templater->formRow('
		
					' . $__templater->callMacro('bh_helper_attachment_photo', 'upload_block', array(
		'attachmentData' => $__vars['attachmentData'],
	), $__vars) . '
		', array(
	)) . '
			
		</div>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('bh_brands/item/save', $__vars['item'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-xf-init' => 'attachment-manager',
	));
	return $__finalCompiled;
}
);