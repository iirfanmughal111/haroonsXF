<?php
// FROM HASH: 64f27e5b11f9feb2be96d02025578a31
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('klUI', 'klUIChoseUsernameColor', ))) {
		$__finalCompiled .= '
	';
		$__templater->includeCss('thuserimprovements_username_color_select.less');
		$__finalCompiled .= '

	<div id="th_name_color_selector">
		';
		$__compilerTemp1 = array();
		if ($__templater->isTraversable($__vars['thuiUsernameColorValues'])) {
			foreach ($__vars['thuiUsernameColorValues'] AS $__vars['color']) {
				$__compilerTemp1[] = array(
					'value' => $__vars['color'],
					'_type' => 'option',
				);
			}
		}
		$__finalCompiled .= $__templater->formRadioRow(array(
			'name' => 'user[th_name_color_id]',
			'value' => $__vars['xf']['visitor']['th_name_color_id'],
		), $__compilerTemp1, array(
			'label' => 'User name color',
		)) . '
	</div>
';
	}
	return $__finalCompiled;
}
);