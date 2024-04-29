<?php
// FROM HASH: 43946fc1822a807d534436b050c74cda
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Export thread attachments (Confirm)');
	$__finalCompiled .= '


' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Please confirm that you want to <b>export all attachments</b> of the following thread' . $__vars['xf']['language']['label_separator'] . '
				<strong>' . $__templater->escape($__vars['thread']['title']) . '</strong>
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'class' => 'js-overlayClose',
		'icon' => 'export',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('threads/export-attachments', $__vars['thread'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);