<?php
// FROM HASH: 9352eee5063de5a85ca9ed23ecf129f0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit TV episode');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['post']['Thread'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('forum', 'snog_edit_episodes', ))) {
		$__compilerTemp1 .= '
				' . $__templater->formTextBoxRow(array(
			'name' => 'tv_title',
			'value' => $__vars['tvshow']['tv_title'],
		), array(
			'label' => 'Title',
		)) . '

				' . $__templater->formNumberBoxRow(array(
			'name' => 'tv_season',
			'value' => $__vars['tvshow']['tv_season'],
			'min' => '0',
		), array(
			'label' => 'Season',
		)) . '

				' . $__templater->formNumberBoxRow(array(
			'name' => 'tv_episode',
			'value' => $__vars['tvshow']['tv_episode'],
			'min' => '1',
		), array(
			'label' => 'Episode',
		)) . '
			
				' . $__templater->formTextBoxRow(array(
			'name' => 'tv_aired',
			'value' => $__vars['tvshow']['tv_aired'],
			'maxlength' => '10',
		), array(
			'label' => 'Air date',
		)) . '

				' . $__templater->formTextAreaRow(array(
			'name' => 'tv_guest',
			'value' => $__vars['tvshow']['tv_guest'],
			'autosize' => 'true',
		), array(
			'label' => 'Guest stars',
		)) . '

				' . $__templater->formTextAreaRow(array(
			'name' => 'tv_plot',
			'value' => $__vars['tvshow']['tv_plot'],
			'autosize' => 'true',
		), array(
			'label' => 'Overview',
		)) . '
			';
	} else {
		$__compilerTemp1 .= '
				<input type="hidden" name="tv_title" value="' . $__templater->escape($__vars['tvshow']['tv_title']) . '" />
				<input type="hidden" name="tv_season" value="' . $__templater->escape($__vars['tvshow']['tv_season']) . '" min="0" />
				<input type="hidden" name="tv_episode" value="' . $__templater->escape($__vars['tvshow']['tv_episode']) . '" min="1" />
			
				<input type="hidden" name="tv_aired" value="' . $__templater->escape($__vars['tvshow']['tv_aired']) . '" />
				<input type="hidden" name="tv_guest" value="' . $__templater->escape($__vars['tvshow']['tv_guest']) . '" />

				<input type="hidden" name="tv_plot" value="' . $__templater->escape($__vars['tvshow']['tv_plot']) . '" />
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
	$__compilerTemp3 = '';
	if ($__vars['quickEdit']) {
		$__compilerTemp3 .= '
					' . $__templater->button('Cancel', array(
			'class' => 'js-cancelButton',
		), '', array(
		)) . '
				';
	}
	$__finalCompiled .= $__templater->form('

	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '

			' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['tvshow']['message'],
		'attachments' => $__vars['attachmentData']['attachments'],
		'data-min-height' => '100',
		'maxlength' => $__vars['xf']['options']['messageMaxLength'],
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
	)) . '

			' . $__templater->formRow('
				' . $__compilerTemp2 . '
				' . $__templater->button('', array(
		'class' => 'button--link u-jsOnly',
		'data-xf-click' => 'preview-click',
		'icon' => 'preview',
	), '', array(
	)) . '
			', array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'simple' : ''),
		'html' => '
				' . $__compilerTemp3 . '
			',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('tv/episode/edit', $__vars['post'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-xf-init' => 'attachment-manager' . (($__templater->method($__vars['post'], 'isFirstPost', array()) AND $__templater->method($__vars['post'], 'canEdit', array())) ? ' post-edit' : ''),
		'data-preview-url' => $__templater->func('link', array('posts/preview', $__vars['post'], ), false),
	));
	return $__finalCompiled;
}
);