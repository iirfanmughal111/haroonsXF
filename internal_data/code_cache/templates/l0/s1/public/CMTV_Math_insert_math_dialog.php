<?php
// FROM HASH: 90d4164f00951f46999e60281d5d6819
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Insert math');
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'CMTV/Math/insert-math-dialog.js',
		'min' => '1',
		'addon' => 'CMTV/Math',
	));
	$__finalCompiled .= '

<form class="block" id="editor_math_form" data-xf-init="insert-math-form">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRadioRow(array(
		'name' => 'math_type',
	), array(array(
		'value' => 'block',
		'checked' => 'checked',
		'label' => 'Block',
		'_type' => 'option',
	),
	array(
		'value' => 'inline',
		'label' => 'Inline',
		'_type' => 'option',
	)), array(
		'label' => 'Type',
		'explain' => 'Inline math is smaller and should be used inside the plain text. Block math is bigger and should be used for big equations and formulas. It also creates line breaks before and after.',
	)) . '
			
			' . $__templater->formTextAreaRow(array(
		'name' => 'latex_input',
		'rows' => '2',
		'autosize' => 'true',
		'autofocus' => '1',
	), array(
		'label' => 'LaTeX input',
		'explain' => '<a href="https://www.latex-tutorial.com/tutorials/amsmath/" target="_blank">How to write math using LaTeX?</a> • <a href="https://katex.org/docs/supported.html" target="_blank">List of supported functions</a>',
	)) . '
		
			<h2 class="block-formSectionHeader">
				<span class="block-formSectionHeader-aligner">
					<span class="preview-loading">' . $__templater->fontAwesome('fa-sync-alt fa-spin', array(
	)) . '</span>
					' . 'Preview' . '
				</span>
			</h2>
			
			<div class="preview-container showing">
				<div class="preview">' . 'Preview' . '</div>
				<div class="error">' . 'Incorrect latex input!' . '</div>
				<div class="empty">' . 'Start typing math in "LaTeX input"...' . '</div>
			</div>
		</div>
		
		' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
		'id' => 'editor_math_submit',
	), array(
	)) . '
	</div>
</form>';
	return $__finalCompiled;
}
);