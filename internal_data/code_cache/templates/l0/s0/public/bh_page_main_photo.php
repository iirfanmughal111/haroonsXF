<?php
// FROM HASH: 3c3864b27ab93ef570cca8594c9a144f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['item']['item_title']));
	$__finalCompiled .= '
';
	$__templater->pageParams['noH1'] = true;
	$__finalCompiled .= '

';
	$__templater->includeCss('bh_item_main_photo_view.less');
	$__finalCompiled .= '


';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['pageAttachments'], 'empty', array())) {
		$__compilerTemp1 .= '
			
				' . $__templater->callMacro('bh_item_list_main_photo_macros', 'item_list', array(
			'Items' => $__vars['pageAttachments'],
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
		'action' => $__templater->func('link', array('bh_item/ownerpage/setmainphoto', $__vars['page'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);