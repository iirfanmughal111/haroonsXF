<?php
// FROM HASH: afe59bec57f5c954c5555583443a06d8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Reactivate account');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['record']['latest_restore_date'] <= ($__vars['xf']['time'] + 63072000)) {
		$__compilerTemp1 .= '
					<p>
						' . 'Latest restore date' . ': ' . $__templater->func('date_dynamic', array($__vars['record']['latest_restore_date'], array(
		))) . '
					</p>
				';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			<div class="block-row">
				<p>
					' . 'Do you want to reactivate your account now?' . '
				</p>
				' . $__compilerTemp1 . '
			</div>
		</div>
		<dl class="formRow formSubmitRow formSubmitRow--sticky" data-xf-init="form-submit-row" style="height: 41.7143px;">
			<dt></dt>
			<dd>
				<div class="formSubmitRow-main">
					<div class="formSubmitRow-bar"></div>
					<div class="formSubmitRow-controls"><button class="button button--primary button--icon"><span class="button-text">' . 'Reactivate account' . '</span></button></div>
				</div>
			</dd>
		</dl>
	</div>
	' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);