<?php
// FROM HASH: 79419bbd56352f788f288141c582a67a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Link to this room');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => '',
		'text' => $__templater->func('link', array('full:chat/room/', $__vars['room'], ), false),
		'successText' => 'URL copied to clipboard.',
	), $__vars) . '
			', array(
		'label' => 'Room URL',
	)) . '
			';
	if ($__vars['xf']['visitor']['is_admin']) {
		$__finalCompiled .= '
				' . $__templater->formRow('
					' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
			'label' => '',
			'text' => $__templater->func('link', array('full:chat/room/', $__vars['room'], array('fullpage' => true, ), ), false),
			'successText' => 'URL copied to clipboard.',
		), $__vars) . '
				', array(
			'label' => 'Room full page URL',
		)) . '

				' . $__templater->formRow('
					' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
			'label' => '',
			'text' => '<iframe src=' . '"' . $__templater->func('link', array('full:chat/room/', $__vars['room'], array('fullpage' => true, ), ), false) . '"' . ' width=' . '"' . '100%' . '"' . ' height=' . '"' . '400' . '"' . ' frameborder=' . '"' . '0' . '"' . '></iframe>',
			'successText' => 'Code copied to clipboard.',
		), $__vars) . '
				', array(
			'label' => 'Room embed code',
		)) . '

				';
		if ($__vars['room']['room_rss']) {
			$__finalCompiled .= '
					<hr class="formRowSep" />
					' . $__templater->formRow('
					' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
				'label' => '',
				'text' => $__templater->func('link', array('full:chat/room/rss', $__vars['room'], ), false),
				'successText' => 'URL copied to clipboard.',
			), $__vars) . '
				', array(
				'label' => 'RSS',
			)) . '
				';
		}
		$__finalCompiled .= '
			';
	}
	$__finalCompiled .= '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Okay',
		'class' => 'js-overlayClose',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
</div>';
	return $__finalCompiled;
}
);