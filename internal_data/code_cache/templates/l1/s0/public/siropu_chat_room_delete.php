<?php
// FROM HASH: d5a6aefc6d33f380af120af5e0be09fa
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'siropu/chat/room.js',
		'min' => 'true',
	));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
			<p>' . 'Please confirm that you want to delete the following' . $__vars['xf']['language']['label_separator'] . '</p>
			<strong>' . $__templater->escape($__vars['room']['room_name']) . '</strong>
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
		'class' => 'js-overlayClose',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('chat/room/delete', $__vars['room'], ), false),
		'class' => 'block',
		'ajax' => 'true',
		'data-xf-init' => 'siropu-chat-room-delete',
	));
	return $__finalCompiled;
}
);