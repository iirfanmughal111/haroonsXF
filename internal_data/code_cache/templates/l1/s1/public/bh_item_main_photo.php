<?php
// FROM HASH: f22f326fa5df4c048c18601426f93d98
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['item']['item_title']));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['items'], 'empty', array())) {
		$__compilerTemp1 .= '
			
				' . $__templater->callMacro('bh_item_list_main_photo_macros', 'item_list', array(
			'Items' => $__vars['items'],
			'Selected' => $__vars['selectedAttachment'],
			'allowInlineMod' => 'true',
		), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '
		</div>
	</div>
	' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
</div>
', array(
		'action' => $__templater->func('link', array('bh_brands/item/setmainphoto', $__vars['item'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);