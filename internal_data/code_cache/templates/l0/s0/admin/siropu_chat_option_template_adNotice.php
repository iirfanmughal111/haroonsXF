<?php
// FROM HASH: 0f293490d7d3f46324185f84c4aa91cc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['option']['option_value'])) {
		foreach ($__vars['option']['option_value'] AS $__vars['value']) {
			$__compilerTemp1 .= '
			<li class="inputGroup">
				' . $__templater->formTextArea(array(
				'name' => $__vars['inputName'] . '[]',
				'value' => $__vars['value'],
				'rows' => '3',
			)) . '
			</li>
		';
		}
	}
	$__finalCompiled .= $__templater->formRow('
	<ul class="listPlain inputGroup-container">
		' . $__compilerTemp1 . '
		<li class="inputGroup" data-xf-init="field-adder" data-increment-format="' . $__templater->escape($__vars['inputName']) . '[]">
			' . $__templater->formTextArea(array(
		'name' => $__vars['inputName'] . '[]',
		'rows' => '3',
	)) . '
		</li>
	</ul>

	' . $__templater->escape($__vars['listedHtml']) . '
', array(
		'label' => $__templater->escape($__vars['option']['title']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'rowtype' => 'input',
	));
	return $__finalCompiled;
}
);