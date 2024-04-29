<?php
// FROM HASH: db9cc082efb59e6308904ddb690179ab
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__vars['ronLogo']) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add Logo');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Update Logo' . $__vars['xf']['language']['label_separator']);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['ronLogo']) {
		$__compilerTemp1 .= '
					' . $__templater->formUploadRow(array(
			'name' => 'logo_img',
			'accept' => '.jpeg,.jpg,.png',
		), array(
			'label' => 'Logo image',
		)) . '

					' . $__templater->formInfoRow('
						<img src="' . $__templater->func('base_url', array('data/brand_img/logo_images/ron-logo.jpg?t=', ), true) . $__templater->escape($__vars['xf']['time']) . '" width="170" height="170" alt="Ron-page-logo-image" />
					', array(
			'rowtype' => 'confirm',
		)) . '
				';
	} else {
		$__compilerTemp1 .= '
					' . $__templater->formUploadRow(array(
			'name' => 'logo_img',
			'accept' => '.jpeg,.jpg,.png',
			'required' => 'true',
		), array(
			'label' => 'Logo image',
		)) . '
				';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
	
				' . $__compilerTemp1 . '

			
		</div>
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('by-rons/upload-logo', $__vars['ron'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);