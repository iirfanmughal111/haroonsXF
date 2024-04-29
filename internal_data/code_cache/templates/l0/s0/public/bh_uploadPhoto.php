<?php
// FROM HASH: e6c4fd9f92c3b561e980847e520012f3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Upload Photos');
	$__finalCompiled .= '


';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'selected' => !$__vars['selectedItem']['item_id'],
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['items'])) {
		foreach ($__vars['items'] AS $__vars['item']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['item']['item_id'],
				'label' => $__templater->escape($__vars['item']['item_title']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
		
			' . $__templater->formHiddenVal('attachment_time', $__vars['attachment_time'], array(
	)) . '
			' . $__templater->formSelectRow(array(
		'name' => 'item_id',
		'value' => $__vars['selectedItem']['item_id'],
	), $__compilerTemp1, array(
		'label' => 'Item',
	)) . '
				
				' . $__templater->formRow('
					' . $__templater->callMacro('bh_helper_attachment_photo', 'upload_block', array(
		'attachmentData' => $__vars['attachmentData'],
	), $__vars) . '
				', array(
	)) . '
			
			' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
				

		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('bh_brands/item/savephoto', $__vars['brand'], ), false),
		'ajax' => 'true',
		'data-xf-init' => 'attachment-manager',
	));
	return $__finalCompiled;
}
);