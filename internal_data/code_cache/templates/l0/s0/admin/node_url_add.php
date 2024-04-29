<?php
// FROM HASH: e019f337b9efd1bcb8c1927bef6c71a5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add Node Url');
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped('Home'), $__templater->func('link', array('nodeUrl', ), false), array(
	));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped('Add Node Url'), $__templater->func('link', array('#', ), false), array(
	));
	$__finalCompiled .= '


';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['forums'])) {
		foreach ($__vars['forums'] AS $__vars['forum']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['forum']['value'],
				'disabled' => $__vars['forum']['disabled'],
				'label' => $__templater->escape($__vars['forum']['label']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'nodeUrl',
		'type' => 'url',
		'autosize' => 'true',
		'row' => '5',
	), array(
		'label' => 'Node Url',
	)) . '
	<ul class="inputList">
		<li>' . $__templater->formSelectRow(array(
		'name' => 'listData[]',
		'value' => $__vars['nodeIds'],
		'multiple' => 'multiple',
		'size' => '7',
	), $__compilerTemp1, array(
		'label' => 'Applicable forums',
	)) . '</li>
	</ul>
		</div>
		    ' . $__templater->formSubmitRow(array(
		'submit' => 'save',
		'fa' => 'fa-save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('nodeUrl/save', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);