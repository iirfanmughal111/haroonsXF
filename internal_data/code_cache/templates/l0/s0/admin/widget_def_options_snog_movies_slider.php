<?php
// FROM HASH: 06f1f5e525cd0aff7ac9aa7147d9b731
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formRadioRow(array(
		'name' => 'options[order]',
		'value' => $__vars['options']['order'],
	), array(array(
		'value' => 'latest',
		'label' => 'Latest movies',
		'_type' => 'option',
	),
	array(
		'value' => 'rating',
		'label' => 'Most rated movies',
		'_type' => 'option',
	),
	array(
		'value' => 'random',
		'label' => 'Random movies',
		'_type' => 'option',
	),
	array(
		'value' => 'last_post_date',
		'label' => 'Last reply date',
		'_type' => 'option',
	)), array(
		'label' => 'Display order',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[limit]',
		'value' => $__vars['options']['limit'],
		'min' => '1',
	), array(
		'label' => 'Maximum entries',
	)) . '

';
	$__compilerTemp1 = array(array(
		'value' => '',
		'label' => 'All forums',
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->method($__vars['nodeTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['node_id'],
				'disabled' => ($__vars['treeEntry']['record']['node_type_id'] != 'Forum'),
				'label' => '
			' . $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['treeEntry']['depth'], ), false), array(array('raw', array()),), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']) . '
		',
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'options[node_ids][]',
		'value' => ($__vars['options']['node_ids'] ?: ''),
		'multiple' => 'multiple',
		'size' => '7',
	), $__compilerTemp1, array(
		'label' => 'Forum limit',
		'explain' => 'Only include threads in the selected forums.',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[show_plot]',
		'value' => '1',
		'selected' => $__vars['options']['show_plot'],
		'label' => 'Plot',
		'_type' => 'option',
	),
	array(
		'name' => 'options[show_genres]',
		'value' => '1',
		'selected' => $__vars['options']['show_genres'],
		'label' => 'Genre',
		'_type' => 'option',
	),
	array(
		'name' => 'options[show_director]',
		'value' => '1',
		'selected' => $__vars['options']['show_director'],
		'label' => 'Director',
		'_type' => 'option',
	),
	array(
		'name' => 'options[show_cast]',
		'value' => '1',
		'selected' => $__vars['options']['show_cast'],
		'label' => 'Cast',
		'_type' => 'option',
	),
	array(
		'name' => 'options[show_status]',
		'value' => '1',
		'selected' => $__vars['options']['show_status'],
		'label' => 'Status',
		'_type' => 'option',
	),
	array(
		'name' => 'options[show_release_date]',
		'value' => '1',
		'selected' => $__vars['options']['show_release_date'],
		'label' => 'Release',
		'_type' => 'option',
	),
	array(
		'name' => 'options[show_runtime]',
		'value' => '1',
		'selected' => $__vars['options']['show_runtime'],
		'label' => 'Runtime',
		'_type' => 'option',
	)), array(
		'label' => 'Elements to show',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[slider][auto]',
		'value' => '1',
		'selected' => $__vars['options']['slider']['auto'],
		'label' => 'Automatically start to play',
		'data-hide' => 'true',
		'_dependent' => array('
			' . $__templater->formNumberBox(array(
		'name' => 'options[slider][pause]',
		'value' => $__vars['options']['slider']['pause'],
		'min' => '1',
	)) . '
			<p class="formRow-explain">' . 'The time (in ms) between each auto transition' . '</p>
		'),
		'_type' => 'option',
	),
	array(
		'name' => 'options[slider][controls]',
		'value' => '1',
		'selected' => $__vars['options']['slider']['controls'],
		'label' => 'Show prev/next buttons',
		'_type' => 'option',
	),
	array(
		'name' => 'options[slider][pauseOnHover]',
		'selected' => $__vars['options']['slider']['pauseOnHover'],
		'label' => 'Pause on hover',
		'_type' => 'option',
	),
	array(
		'name' => 'options[slider][loop]',
		'selected' => $__vars['options']['slider']['loop'],
		'label' => 'Loop slides',
		'_type' => 'option',
	),
	array(
		'name' => 'options[slider][pager]',
		'selected' => $__vars['options']['slider']['pager'],
		'label' => 'Display pager',
		'_type' => 'option',
	)), array(
		'label' => 'Slider options',
	));
	return $__finalCompiled;
}
);