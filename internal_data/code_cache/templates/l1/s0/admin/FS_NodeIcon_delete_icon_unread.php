<?php
// FROM HASH: 16e41bfbe675cea53c82beb2b8e79275
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Please confirm delete icon');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Please confirm that you want to delete the following' . $__vars['xf']['language']['label_separator'] . '
				
				' . $__templater->formRow('
					<img src="' . $__templater->func('base_url', array($__templater->method($__vars['node'], 'getIconUnread', array()), ), true) . '" />
				', array(
		'rowtype' => 'fullWidth noLabel',
	)) . '
			', array(
		'rowtype' => 'confirm',
	)) . '
			' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
		'rowtype' => 'simple',
	)) . '
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('forums/delete-icon-unread', $__vars['node'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);