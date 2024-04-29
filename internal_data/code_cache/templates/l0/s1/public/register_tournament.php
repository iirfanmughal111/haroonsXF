<?php
// FROM HASH: 5de7b366d9f643ca034f17dbb13fecc3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['xf']['visitor']['user_id']) {
		$__compilerTemp1 .= '
			' . $__templater->formInfoRow('
				' . 'Are you sure you want to register tournament ?' . '
				
			', array(
			'rowtype' => 'confirm',
		)) . '
		
		' . $__templater->formSubmitRow(array(
		), array(
			'html' => '
				' . $__templater->button('Register', array(
			'type' => 'submit',
			'class' => 'button--primary',
		), '', array(
		)) . '
			',
		)) . '
			
			';
	} else {
		$__compilerTemp1 .= '
			' . $__templater->formInfoRow('
			' . 'You need an account to do that !Click  <a href="' . $__templater->func('link', array('register', ), true) . '">here</a> to create an account.' . '
			', array(
			'rowtype' => 'confirm',
		)) . '
			
			';
	}
	$__finalCompiled .= $__templater->form('
	

	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '
			</div>
			
			
		
		
		
	</div>
	' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'action' => $__templater->func('link', array('uptourn/Tournamentregister', $__vars['tournament'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);