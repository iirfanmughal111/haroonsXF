<?php
// FROM HASH: e7df914ac375eddf19f7809e60796c6f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<span class="u-anchorTarget" id="job-snogMovies"></span>

';
	$__compilerTemp1 = array(array(
		'value' => 'Snog\\Movies:MovieRatingRebuild',
		'label' => 'Recalculate ratings',
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\Movies:MovieRebuild',
		'label' => 'Rebuild general movie info',
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\Movies:MovieCreditsRebuild',
		'label' => 'Movie casts & crew',
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\Movies:MoviePersonsRebuild',
		'label' => 'Rebuild person data',
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\Movies:MovieVideosRebuild',
		'label' => 'Rebuild videos',
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\Movies:MovieWatchProvidersRebuild',
		'label' => 'Rebuild watch providers',
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\Movies:MovieProductionCompaniesRebuild',
		'label' => 'Rebuild movie production companies',
		'_type' => 'option',
	));
	if ($__templater->func('is_addon_active', array('ThemeHouse/Covers', ), false)) {
		$__compilerTemp1[] = array(
			'value' => 'Snog\\Movies:MovieThreadCoverUpdate',
			'label' => 'Rebuild thread covers',
			'_type' => 'option',
		);
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<h2 class="block-header">[OzzModz] TMDb Movie Thread Starter</h2>
		<div class="block-body">
			' . $__templater->formRadioRow(array(
		'name' => 'job',
	), $__compilerTemp1, array(
	)) . '
		</div>
		
		' . $__templater->formSubmitRow(array(
		'submit' => 'Rebuild now',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('tools/rebuild', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);