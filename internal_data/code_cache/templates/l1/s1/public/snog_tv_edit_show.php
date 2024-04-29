<?php
// FROM HASH: b1be01d3b59c64cf351ecf0a6c5aa0d2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit TV show');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['thread'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('forum', 'snog_edit_shows', ))) {
		$__compilerTemp1 .= '
				' . $__templater->formTextBoxRow(array(
			'name' => 'tv_title',
			'value' => $__vars['tvshow']['tv_title'],
		), array(
			'label' => 'Title',
		)) . '

				' . $__templater->formTextBoxRow(array(
			'name' => 'tv_genres',
			'value' => $__vars['tvshow']['tv_genres'],
		), array(
			'label' => 'Genre',
		)) . '

				' . $__templater->formTextAreaRow(array(
			'name' => 'tv_director',
			'value' => $__vars['tvshow']['tv_director'],
			'autosize' => 'true',
		), array(
			'label' => 'Creator',
		)) . '

				' . $__templater->formTextAreaRow(array(
			'name' => 'tv_cast',
			'value' => $__vars['tvshow']['tv_cast'],
			'autosize' => 'true',
		), array(
			'label' => 'Cast',
		)) . '

				' . $__templater->formDateInputRow(array(
			'name' => 'first_air_date',
			'value' => ($__vars['tvshow']['first_air_date'] ? $__templater->func('date', array($__vars['tvshow']['first_air_date'], ), false) : ''),
		), array(
			'label' => 'First aired',
		)) . '
				' . $__templater->formDateInputRow(array(
			'name' => 'last_air_date',
			'value' => ($__vars['tvshow']['last_air_date'] ? $__templater->func('date', array($__vars['tvshow']['last_air_date'], ), false) : ''),
		), array(
			'label' => 'Last air date',
		)) . '
				
				' . $__templater->formTextBoxRow(array(
			'name' => 'status',
			'value' => $__vars['tvshow']['status'],
		), array(
			'label' => 'Show status',
		)) . '

				' . $__templater->formTextAreaRow(array(
			'name' => 'tv_plot',
			'value' => $__vars['tvshow']['tv_plot'],
			'autosize' => 'true',
		), array(
			'label' => 'Overview',
		)) . '
				
				' . $__templater->formTextBoxRow(array(
			'name' => 'tv_trailer',
			'value' => $__vars['tvshow']['tv_trailer'],
		), array(
			'label' => 'Trailer',
			'explain' => 'Enter either a youtube video ID or a youtube link',
		)) . '
			';
	} else {
		$__compilerTemp1 .= '
				<input type="hidden" name="tv_title" value="' . $__templater->escape($__vars['tvshow']['tv_title']) . '" />
				<input type="hidden" name="tv_genres" value="' . $__templater->escape($__vars['tvshow']['tv_genres']) . '" />
				<input type="hidden" name="tv_director" value="' . $__templater->escape($__vars['tvshow']['tv_director']) . '" />
				<input type="hidden" name="tv_cast" value="' . $__templater->escape($__vars['tvshow']['tv_cast']) . '" />
				<input type="hidden" name="tv_release" value="' . $__templater->escape($__vars['tvshow']['tv_release']) . '" />
				<input type="hidden" name="tv_plot" value="' . $__templater->escape($__vars['tvshow']['tv_plot']) . '" />
				<input type="hidden" name="tv_trailer" value="' . $__templater->escape($__vars['tvshow']['tv_trailer']) . '" />
			';
	}
	$__compilerTemp2 = '';
	if ($__vars['tvshow']['comment']) {
		$__compilerTemp2 .= '
				' . $__templater->formEditorRow(array(
			'name' => 'message',
			'value' => $__vars['tvshow']['comment'],
			'attachments' => $__vars['attachmentData']['attachments'],
			'data-min-height' => '100',
			'maxlength' => $__vars['xf']['options']['messageMaxLength'],
		), array(
			'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		)) . '

				';
		$__compilerTemp3 = '';
		if ($__vars['attachmentData']) {
			$__compilerTemp3 .= '
						' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
				'attachmentData' => $__vars['attachmentData'],
			), $__vars) . '
					';
		}
		$__compilerTemp2 .= $__templater->formRow('
					' . $__compilerTemp3 . '
					' . $__templater->button('', array(
			'class' => 'button--link u-jsOnly',
			'data-xf-click' => 'preview-click',
			'icon' => 'preview',
		), '', array(
		)) . '
				', array(
			'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		)) . '
			';
	}
	$__compilerTemp4 = '';
	if ($__vars['quickEdit']) {
		$__compilerTemp4 .= '
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
			
			' . $__compilerTemp2 . '
	
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'simple' : ''),
		'html' => '
				' . $__compilerTemp4 . '
			',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('tv/edit', $__vars['tvshow'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-xf-init' => 'attachment-manager' . (($__templater->method($__vars['post'], 'isFirstPost', array()) AND $__templater->method($__vars['thread'], 'canEdit', array())) ? ' post-edit' : ''),
		'data-preview-url' => $__templater->func('link', array('posts/preview', $__vars['post'], ), false),
	));
	return $__finalCompiled;
}
);