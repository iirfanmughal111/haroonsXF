<?php
// FROM HASH: 7a7bafbb319f9eee65147562a7868202
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['room']['room_password']) {
		$__compilerTemp1 .= '
				' . $__templater->formInfoRow('This room is password protected. To join the room, please provide the room password.', array(
		)) . '
				' . $__templater->formTextBoxRow(array(
			'name' => 'password',
		), array(
			'label' => 'Password',
		)) . '
				' . $__templater->formSubmitRow(array(
			'submit' => 'Join room',
		), array(
			'rowtype' => 'simple',
		)) . '
			';
	} else {
		$__compilerTemp1 .= '
				' . $__templater->formInfoRow('You don\'t have the permission to join this room.', array(
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-header">
			' . $__templater->escape($__vars['room']['room_name']) . '
			<div class="block-desc">' . $__templater->escape($__vars['room']['room_description']) . '</div>
		</div>
		<div class="block-body">
			' . $__compilerTemp1 . '
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('chat/room', $__vars['room'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);