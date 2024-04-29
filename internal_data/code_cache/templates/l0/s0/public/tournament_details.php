<?php
// FROM HASH: fa98dc5d31bc4502cad6db65accbb75c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['tournament']->{'tourn_title'}));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['xf']['visitor']['user_id']) {
		$__compilerTemp1 .= '
				';
		$__compilerTemp2 = '';
		if ($__vars['user']) {
			$__compilerTemp2 .= '	
			
				' . $__templater->filter($__vars['tournament']->{'tourn_desc'}, array(array('raw', array()),), true) . '
				
			
				
			';
		} else {
			$__compilerTemp2 .= '
				' . 'You have not register on this tournament ' . '
				';
		}
		$__compilerTemp1 .= $__templater->formInfoRow('
			' . $__compilerTemp2 . '
		', array(
			'rowtype' => 'confirm',
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
		'action' => '',
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);