<?php
// FROM HASH: 66be20dd5fd7325806814528c0bbe59a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Deactivate account');
	$__finalCompiled .= '

';
	$__templater->wrapTemplate('account_wrapper', $__vars);
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('klUI', 'klUISelfReactivate', ))) {
		$__compilerTemp1 .= '
					';
		if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('klUI', 'klUISelfReactivationTime', )) === -1) {
			$__compilerTemp1 .= '
						' . 'You may reactivate your account at any point in the future.' . '
						';
		} else {
			$__compilerTemp1 .= '
						' . 'You may reactivate your account within the next ' . $__templater->escape($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('klUI', 'klUISelfReactivationTime', ))) . ' days after deactivation.' . '
					';
		}
		$__compilerTemp1 .= '
					';
	} else {
		$__compilerTemp1 .= '
					' . 'Account deactivation is permanent and cannot be undone.' . '
				';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			<div class="block-row">
				' . $__compilerTemp1 . '
			</div>
		</div>
		<dl class="formRow formSubmitRow formSubmitRow--sticky" data-xf-init="form-submit-row" style="height: 41.7143px;">
			<dt></dt>
			<dd>
				<div class="formSubmitRow-main">
					<div class="formSubmitRow-bar"></div>
					<div class="formSubmitRow-controls"><button class="button button--primary button--icon"><span class="button-text">' . 'Deactivate account' . '</span></button></div>
				</div>
			</dd>
		</dl>
	</div>
	' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'action' => $__templater->func('link', array('account/thui-deactivate', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);