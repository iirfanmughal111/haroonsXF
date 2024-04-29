<?php
// FROM HASH: 63173692fe0b7cd105aca22123405df6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit rules');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formEditorRow(array(
		'name' => 'rules',
		'value' => $__vars['xf']['options']['siropuChatRules'],
	), array(
		'rowtype' => 'fullWidth noLabel',
	)) . '
		</div>
	</div>
	' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
', array(
		'action' => $__templater->func('link', array('chat/edit-rules', ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);