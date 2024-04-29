<?php
// FROM HASH: d8a9029ffc86557de2746fd7c2e0071a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit comment');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['content'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['comment'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp1 .= '
				' . $__templater->formRow('
					' . $__templater->callMacro('helper_action', 'author_alert', array(
			'row' => false,
		), $__vars) . '
				', array(
			'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		)) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__vars['quickEdit']) {
		$__compilerTemp2 .= '
					' . $__templater->button('Cancel', array(
			'class' => 'js-cancelButton',
			'icon' => 'cancel',
		), '', array(
		)) . '
				';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			<span class="u-anchorTarget js-editContainer"></span>
			' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['comment']['message'],
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		'label' => 'Message',
	)) . '

			<div class="js-previewContainer"></div>

			' . $__templater->formRow('
				' . $__templater->callMacro('helper_action', 'edit_type', array(
		'canEditSilently' => $__templater->method($__vars['comment'], 'canEditSilently', array()),
	), $__vars) . '
			', array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
	)) . '

			' . $__compilerTemp1 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'simple' : ''),
		'html' => '
				' . $__templater->button('', array(
		'class' => 'u-jsOnly',
		'data-xf-click' => 'preview-click',
		'icon' => 'preview',
	), '', array(
	)) . '
				' . $__compilerTemp2 . '
			',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('media/comments/edit', $__vars['comment'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-preview-url' => $__templater->func('link', array(('media/' . (($__vars['content']['content_type'] == 'xfmg_media') ? 'media' : 'album')) . '-comments/preview', $__vars['content'], ), false),
	));
	return $__finalCompiled;
}
);