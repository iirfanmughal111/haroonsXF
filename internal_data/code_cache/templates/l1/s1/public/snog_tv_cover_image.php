<?php
// FROM HASH: cf92d6bb3aad35385658eec122735aec
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('thcovers_modify_cover_image');
	$__finalCompiled .= '

';
	$__templater->includeCss('th_covers.less');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['hasNewBackdrop']) {
		$__compilerTemp1 .= '
				' . 'Backdrop available' . '<br /><br />
				<span style="text-align:center;"><img src="https://image.tmdb.org/t/p/' . $__templater->escape($__vars['xf']['options']['TvThreads_backdropCoverSize']) . $__templater->escape($__vars['backdropPath']) . '" alt="" /></span>
			';
	} else {
		$__compilerTemp1 .= '
				' . 'New backdrop is not available' . '
				';
		if ($__vars['backdropPath']) {
			$__compilerTemp1 .= '
					<strong>' . 'Re-upload old one?' . '</strong>
				';
		}
		$__compilerTemp1 .= '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		' . $__templater->formInfoRow('
			' . $__compilerTemp1 . '
		', array(
		'rowtype' => 'confirm',
	)) . '

		' . $__templater->formSubmitRow(array(
		'submit' => 'Okay',
		'class' => 'js-overlayClose',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('covers/tv-update', array('content_type' => $__vars['contentType'], 'content_id' => $__vars['contentId'], ), ), false),
		'ajax' => 'true',
		'upload' => 'true',
		'class' => 'block',
		'data-xf-init' => 'cover-upload',
	));
	return $__finalCompiled;
}
);