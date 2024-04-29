<?php
// FROM HASH: 281969f5e1805dd76cdef1ca0ae26aef
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->form('

	<div class="menu-row menu-row--separated">
		' . 'Complaints' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formSelect(array(
		'name' => 'fs_wr_complains',
		'value' => $__vars['filters']['fs_wr_complains'],
	), array(array(
		'value' => 'solved_percentage',
		'label' => 'Most Solved',
		'_type' => 'option',
	),
	array(
		'value' => 'unsolved_percentage',
		'label' => 'Most Unsolved',
		'_type' => 'option',
	),
	array(
		'value' => 'issue_count',
		'label' => 'Most Complains',
		'_type' => 'option',
	))) . '
		</div>
	</div>	

	<div class="menu-footer">
		<span class="menu-footer-controls">
			' . $__templater->button('Filter', array(
		'type' => 'submit',
		'class' => 'button--primary',
	), '', array(
	)) . '
		</span>
	</div>

	' . $__templater->formHiddenVal('search', '1', array(
	)) . '
', array(
		'action' => $__templater->func('link', array('web-ranking', ), false),
	));
	return $__finalCompiled;
}
);