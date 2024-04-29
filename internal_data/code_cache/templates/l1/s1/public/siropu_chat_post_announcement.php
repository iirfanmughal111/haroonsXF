<?php
// FROM HASH: 3d7bab19fdab07dd834a5ec22d1e32e1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Post announcement');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__vars['rooms']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRadioRow(array(
		'name' => 'target',
		'value' => 'chat',
	), array(array(
		'value' => 'chat',
		'label' => 'All rooms',
		'_type' => 'option',
	),
	array(
		'value' => 'room',
		'label' => 'Selected rooms',
		'_dependent' => array('
						' . $__templater->formSelect(array(
		'name' => 'room_id',
		'multiple' => 'true',
	), $__compilerTemp1) . '
					'),
		'_type' => 'option',
	)), array(
		'label' => 'Post in' . $__vars['xf']['language']['ellipsis'],
	)) . '
			' . $__templater->formEditorRow(array(
		'name' => 'message',
	), array(
		'label' => 'Message',
	)) . '
			' . $__templater->formTextBoxRow(array(
		'name' => 'bot_name',
		'maxlength' => '50',
	), array(
		'label' => 'Bot name',
		'explain' => 'You can set a bot name if you don\'t want the announcement to be posted with your username.',
		'hint' => 'Optional',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Post announcement',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('chat/post-announcement', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);