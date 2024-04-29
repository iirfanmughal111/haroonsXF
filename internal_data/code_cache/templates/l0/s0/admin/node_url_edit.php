<?php
// FROM HASH: 23897b3bd4e8717f39294cbfc72eee76
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit Node Url' . ' : ' . $__templater->escape($__vars['data']['title']));
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped('Home'), $__templater->func('link', array('nodeUrl', ), false), array(
	));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped('Edit Node Url'), $__templater->func('link', array('#', ), false), array(
	));
	$__finalCompiled .= '


' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'nodeUrl',
		'value' => $__vars['data']['node_url'],
		'autosize' => 'true',
		'type' => 'url',
		'row' => '5',
	), array(
		'label' => 'Edit Url',
	)) . '
		</div>
		    ' . $__templater->formSubmitRow(array(
		'submit' => 'Update',
		'fa' => 'fa-edit',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('nodeUrl/edit', $__vars['data'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);