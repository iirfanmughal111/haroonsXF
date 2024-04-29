<?php
// FROM HASH: 9cf132f872fd240ed1c027c13f3675a4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add TV show info');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'tmdb',
		'value' => '',
	), array(
		'label' => 'TMDb TV show link',
	)) . '
			
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'changetitle',
		'value' => '1',
		'selected' => false,
		'label' => 'Change thread title to TV show title',
		'_type' => 'option',
	)), array(
	)) . '
		</div>
		
		<div class="formRow formSubmitRow">
			<div class="formSubmitRow-main">
				<div class="formSubmitRow-bar"></div>
				<div class="formSubmitRow-controls" style="padding: 5px 0 5px 0; !important;">
					<div style="text-align:center;margin-left:auto;margin-right:auto;">
						' . $__templater->button('Add TV show info', array(
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
		'action' => $__templater->func('link', array('tv/addinfo', $__vars['thread'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);