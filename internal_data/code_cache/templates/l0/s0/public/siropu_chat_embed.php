<?php
// FROM HASH: e45049528d19eabb44f60ebe9057e95b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Embed');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextAreaRow(array(
		'readonly' => 'readonly',
		'value' => '<iframe src=' . '"' . $__templater->func('link', array('full:chat/fullpage', ), false) . '"' . ' width=' . '"' . '100%' . '"' . ' height=' . '"' . '400' . '"' . ' frameborder=' . '"' . '0' . '"' . '></iframe>',
		'rows' => '3',
	), array(
		'label' => 'Embed code',
	)) . '
		</div>
	</div>
', array(
		'class' => 'block',
	));
	return $__finalCompiled;
}
);