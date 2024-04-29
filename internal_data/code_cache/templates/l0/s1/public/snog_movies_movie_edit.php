<?php
// FROM HASH: 9594a08df492f65a8d6c7319482d7894
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit movie');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['thread'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['movie']['comment']) {
		$__compilerTemp1 .= '
				' . $__templater->formEditorRow(array(
			'name' => 'message',
			'value' => $__vars['movie']['comment'],
			'attachments' => $__vars['attachmentData']['attachments'],
			'data-min-height' => '100',
			'maxlength' => $__vars['xf']['options']['messageMaxLength'],
		), array(
			'label' => 'Comment',
		)) . '

				';
		$__compilerTemp2 = '';
		if ($__vars['attachmentData']) {
			$__compilerTemp2 .= '
						' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
				'attachmentData' => $__vars['attachmentData'],
			), $__vars) . '
					';
		}
		$__compilerTemp1 .= $__templater->formRow('
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
			' . $__templater->formTextBoxRow(array(
		'name' => 'tmdb_title',
		'value' => $__vars['movie']['tmdb_title'],
	), array(
		'label' => 'Title',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'tmdb_tagline',
		'value' => $__vars['movie']['tmdb_tagline'],
	), array(
		'label' => 'Tagline',
	)) . '
			
			' . $__templater->formTextBoxRow(array(
		'name' => 'tmdb_genres',
		'value' => $__vars['movie']['tmdb_genres'],
	), array(
		'label' => 'Genre',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'tmdb_director',
		'value' => $__vars['movie']['tmdb_director'],
		'autosize' => 'true',
	), array(
		'label' => 'Director',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'tmdb_cast',
		'value' => $__vars['movie']['tmdb_cast'],
		'autosize' => 'true',
	), array(
		'label' => 'Cast',
	)) . '
			
			' . $__templater->formTextBoxRow(array(
		'name' => 'tmdb_status',
		'value' => $__vars['movie']['tmdb_status'],
	), array(
		'label' => 'Status',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'tmdb_release',
		'value' => $__vars['movie']['tmdb_release'],
	), array(
		'label' => 'Release',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'tmdb_runtime',
		'value' => $__vars['movie']['tmdb_runtime'],
	), array(
		'label' => 'Runtime',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'tmdb_plot',
		'value' => $__vars['movie']['tmdb_plot'],
		'autosize' => 'true',
	), array(
		'label' => 'Plot',
	)) . '
			
			' . $__templater->formTextBoxRow(array(
		'name' => 'tmdb_trailer',
		'value' => $__vars['movie']['tmdb_trailer'],
	), array(
		'label' => 'Trailer',
		'explain' => 'Enter either a youtube video ID or a youtube link',
	)) . '

			' . $__compilerTemp1 . '
	
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
		'action' => $__templater->func('link', array('movies/edit', $__vars['movie'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-xf-init' => 'attachment-manager' . (($__templater->method($__vars['post'], 'isFirstPost', array()) AND $__templater->method($__vars['thread'], 'canEdit', array())) ? ' post-edit' : ''),
		'data-preview-url' => $__templater->func('link', array('posts/preview', $__vars['post'], ), false),
	));
	return $__finalCompiled;
}
);