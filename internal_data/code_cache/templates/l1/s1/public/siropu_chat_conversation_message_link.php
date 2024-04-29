<?php
// FROM HASH: 1f6483a367e9a73e8a9784df87870d21
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Link to this message');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => '',
		'text' => $__templater->func('link', array('full:chat/conversation/message', $__vars['message'], ), false),
		'successText' => 'URL copied to clipboard.',
	), $__vars) . '
			', array(
		'label' => 'Message URL',
	)) . '
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