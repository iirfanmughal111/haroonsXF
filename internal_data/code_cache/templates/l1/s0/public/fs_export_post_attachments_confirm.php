<?php
// FROM HASH: 0039e62f3b88d9f668a2429405fb4b29
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Export post attachments (Confirm)');
	$__finalCompiled .= '


' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Please confirm that you want to <b>export all attachments</b> of the following post' . $__vars['xf']['language']['label_separator'] . '
				<strong>' . $__templater->escape($__vars['post']['Thread']['title']) . '</strong>
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
		'action' => $__templater->func('link', array('posts/export-attachments', $__vars['post'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);