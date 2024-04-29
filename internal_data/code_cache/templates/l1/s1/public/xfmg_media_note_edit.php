<?php
// FROM HASH: f538fc694700d703ae8f26e797e4648f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ((!$__vars['note']['note_id']) OR ($__vars['note']['note_type'] == 'user_tag')) {
		$__compilerTemp1 .= '
					<span class="tabs-tab' . (((!$__vars['note']['note_id']) OR ($__vars['note']['note_type'] == 'user_tag')) ? ' is-active' : '') . '">' . 'Tag a user' . '</span>
				';
	}
	$__compilerTemp2 = '';
	if ((!$__vars['note']['note_id']) OR ($__vars['note']['note_type'] == 'note')) {
		$__compilerTemp2 .= '
					<span class="tabs-tab' . (($__vars['note']['note_type'] == 'user_tag') ? ' is-active' : '') . '">' . 'Write a note' . '</span>
				';
	}
	$__compilerTemp3 = '';
	if ((!$__vars['note']['note_id']) OR ($__vars['note']['note_type'] == 'user_tag')) {
		$__compilerTemp3 .= '
			<li class="' . (((!$__vars['note']['note_id']) OR ($__vars['note']['note_type'] == 'user_tag')) ? 'is-active' : '') . '">
				' . $__templater->formTextBox(array(
			'name' => 'tagged_username',
			'value' => ($__vars['note']['TaggedUser'] ? $__vars['note']['TaggedUser']['username'] : $__vars['note']['tagged_username']),
			'placeholder' => 'Name' . $__vars['xf']['language']['ellipsis'],
			'maxlength' => $__templater->func('max_length', array($__vars['note'], 'tagged_username', ), false),
			'ac' => 'single',
		)) . '
			</li>
		';
	}
	$__compilerTemp4 = '';
	if ((!$__vars['note']['note_id']) OR ($__vars['note']['note_type'] == 'note')) {
		$__compilerTemp4 .= '
			<li class="' . (($__vars['note']['note_type'] == 'user_tag') ? 'is-active' : '') . '">
				' . $__templater->formTextArea(array(
			'name' => 'note_text',
			'value' => $__vars['note']['note_text'],
			'placeholder' => 'Write something' . $__vars['xf']['language']['ellipsis'],
			'maxlength' => $__templater->func('max_length', array($__vars['note'], 'note_text', ), false),
			'autosize' => 'true',
			'rows' => '2',
		)) . '
			</li>
		';
	}
	$__compilerTemp5 = '';
	if ($__vars['note']['note_id'] AND $__templater->method($__vars['note'], 'canDelete', array())) {
		$__compilerTemp5 .= '
			' . $__templater->button('', array(
			'type' => 'submit',
			'name' => 'delete',
			'class' => 'button--icon button--padded button--icon--delete button--iconOnly',
		), '', array(
		)) . '
		';
	}
	$__finalCompiled .= $__templater->form('
	<h4 class="block-minorTabHeader tabs" data-xf-init="tabs">
		<span class="hScroller" data-xf-init="h-scroller">
			<span class="hScroller-scroll">
				' . $__compilerTemp1 . '
				' . $__compilerTemp2 . '
			</span>
		</span>
	</h4>

	<ul class="noteTooltip-row tabPanes">
		' . $__compilerTemp3 . '
		' . $__compilerTemp4 . '
	</ul>

	<div class="noteTooltip-footer">
		' . $__templater->button('', array(
		'type' => 'submit',
		'icon' => 'save',
		'class' => 'button--primary button--padded',
	), '', array(
	)) . '
		' . $__compilerTemp5 . '
		' . $__templater->button('', array(
		'type' => 'reset',
		'icon' => 'cancel',
		'class' => 'button--padded js-cancelButton',
	), '', array(
	)) . '
	</div>

	' . $__templater->formHiddenVal('note_data', $__templater->filter($__vars['note']['note_data'], array(array('json', array()),), false), array(
		'class' => 'js-noteData',
	)) . '
	' . $__templater->formHiddenVal('note_id', $__vars['note']['note_id'], array(
	)) . '
', array(
		'action' => $__templater->func('link', array('media/note-edit', $__vars['mediaItem'], ), false),
		'class' => 'noteTooltip js-noteTooltipForm',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);