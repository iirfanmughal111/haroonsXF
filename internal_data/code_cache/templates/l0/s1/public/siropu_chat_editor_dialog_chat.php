<?php
// FROM HASH: 4626bc6eb10d04c96e0a75eb2d80e181
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Chat image uploads');
	$__finalCompiled .= '

';
	$__templater->includeCss('attachments.less');
	$__finalCompiled .= '
';
	$__templater->includeCss('siropu_chat_attachments.less');
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'prod' => 'xf/attachment_manager-compiled.js',
		'dev' => 'vendor/flow.js/flow-compiled.js, xf/attachment_manager.js',
	));
	$__finalCompiled .= '

';
	$__vars['allowedQuota'] = $__templater->preEscaped($__templater->escape($__templater->method($__vars['xf']['visitor'], 'canUploadSiropuChatImages', array())));
	$__compilerTemp1 = '';
	if ($__vars['allowedQuota'] < 1000) {
		$__compilerTemp1 .= '
				<div class="block-rowMessage block-rowMessage--warning block-rowMessage--small">
					' . 'You may upload up to ' . $__templater->escape($__vars['allowedQuota']) . ' images. Delete unwanted images to upload more.' . '
				</div>
			';
	}
	$__compilerTemp2 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp2 .= '
				' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
		), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body block-row js-attachmentUploads">
			' . '' . '
			' . $__compilerTemp1 . '
			' . $__compilerTemp2 . '
		</div>
	</div>
	' . $__templater->formSubmitRow(array(
	), array(
		'rowtype' => 'simple',
		'html' => '
			' . $__templater->button('Insert selected', array(
		'icon' => 'add',
		'class' => 'button--primary siropuChatDialogInsert',
	), '', array(
	)) . '
		',
	)) . '
', array(
		'action' => $__templater->func('link', array('attachments/upload', null, array('type' => 'siropu_chat', 'context' => $__vars['attachmentData']['context'], 'hash' => $__vars['attachmentData']['hash'], ), ), false),
		'ajax' => 'true',
		'class' => 'block siropuChatUploads',
		'data-xf-init' => 'attachment-manager',
		'autofocus' => 'off',
	));
	return $__finalCompiled;
}
);