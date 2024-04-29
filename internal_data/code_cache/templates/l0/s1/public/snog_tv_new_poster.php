<?php
// FROM HASH: db6696200f1855c408b4da1a391a0ab5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Check for new poster');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['newposter']) {
		$__compilerTemp1 .= '
					' . 'A new poster is available' . '<br /><br />
					<span style="text-align:center;"><img src="https://image.tmdb.org/t/p/' . $__templater->escape($__vars['xf']['options']['TvThreads_largePosterSize']) . $__templater->escape($__vars['posterpath']) . '" alt="" /></span>
				';
	} else {
		$__compilerTemp1 .= '
					' . 'A new poster is not available' . '
					';
		if ($__vars['posterpath']) {
			$__compilerTemp1 .= '
						<strong>' . 'Reupload old poster?' . '</strong>
					';
		}
		$__compilerTemp1 .= '
				';
	}
	$__compilerTemp2 = '';
	if ($__vars['posterpath']) {
		$__compilerTemp2 .= '
				<input type="hidden" name="posterpath" value="' . $__templater->escape($__vars['posterpath']) . '" />
				' . $__templater->formSubmitRow(array(
			'submit' => 'Save poster',
		), array(
			'rowtype' => 'simple',
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . $__compilerTemp1 . '
			', array(
		'rowtype' => 'confirm',
	)) . '
			
			' . $__compilerTemp2 . '

		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('tv/poster', $__vars['tvshow'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);