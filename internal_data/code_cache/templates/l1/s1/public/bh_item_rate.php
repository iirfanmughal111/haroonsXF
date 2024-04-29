<?php
// FROM HASH: 1303ac61e3e38b2f8fa9f9a2898e1db4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Rate this Item');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['existingRating']) {
		$__compilerTemp1 .= '
				' . $__templater->formInfoRow('
					' . 'You have already rated this Item. Re-rating it will remove your existing rating or review.' . '
				', array(
			'rowtype' => 'confirm',
		)) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__vars['xf']['options']['bh_ReviewRequired']) {
		$__compilerTemp2 .= '
						' . 'Review is required' . '
					';
	}
	$__compilerTemp3 = '';
	if ($__vars['xf']['options']['bh_MinimumReviewLength']) {
		$__compilerTemp3 .= '
						<span id="js-resourceReviewLength">' . 'Your review must be at least ' . $__templater->escape($__vars['xf']['options']['bh_MinimumReviewLength']) . ' characters.' . '</span>
					';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '
			
			' . $__templater->callMacro('rating_macros', 'rating', array(
		'currentRating' => $__vars['item']['ItemRatings'][$__vars['xf']['visitor']['user_id']]['rating'],
	), $__vars) . '
			
		
				' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['item']['ItemRatings'][$__vars['xf']['visitor']['user_id']]['message'],
		'data-min-height' => '100',
		'data-xf-init' => 'min-length',
		'data-min-length' => $__vars['xf']['options']['bh_MinimumReviewLength'],
		'data-allow-empty' => ($__vars['xf']['options']['bh_ReviewRequired'] ? 'false' : 'true'),
		'data-toggle-target' => '#js-resourceReviewLength',
		'maxlength' => $__vars['xf']['options']['messageMaxLength'],
	), array(
		'label' => 'Review',
		'hint' => ($__vars['xf']['options']['bh_ReviewRequired'] ? 'Required' : ''),
		'explain' => '
					' . 'Explain why you\'re giving this rating. Reviews which are not constructive may be removed without notice.' . '
					' . $__compilerTemp2 . '
					' . $__compilerTemp3 . '
				',
	)) . '

		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Submit rating',
		'icon' => 'rate',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('bh_brands/item/rate', $__vars['item'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);