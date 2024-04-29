<?php
// FROM HASH: ca7b9b974b7d7d4f30015aef8d6746bf
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['tvshow']['tv_title']));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
			<div class="block-body">
				' . $__templater->callMacro('rating_macros', 'rating', array(
		'row' => true,
		'name' => 'rating',
		'currentRating' => $__vars['userRating']['rating'],
		'rowLabel' => 'Rating',
		'rowHint' => '',
		'rowExplain' => 'If you have already rated this show, your current rating is shown',
	), $__vars) . '
		</div>

		<div class="formRow formSubmitRow">
			<div class="formSubmitRow-main">
				<div class="formSubmitRow-bar"></div>
				<div class="formSubmitRow-controls" style="padding: 5px 0 5px 0; !important;">
					<div style="text-align:center;margin-left:auto;margin-right:auto;">
						' . $__templater->button('Submit rating', array(
		'type' => 'submit',
		'accesskey' => 's',
		'class' => 'button button--icon button--icon--save',
	), '', array(
	)) . '
					</div>
				</div>
			</div>
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('tv/rate', $__vars['tvshow'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);