<?php
// FROM HASH: 657bb7b782679cf336bbc25f8aa3ec06
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<span class="u-anchorTarget" id="job-snogTv"></span>

';
	$__compilerTemp1 = array(array(
		'value' => 'Snog\\TV:TvRatingRebuild',
		'label' => 'Recalculate TV ratings',
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\TV:TvForumRatingRebuild',
		'label' => 'Recalculate TV forum ratings',
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\TV:TvThreadRebuild',
		'label' => 'Rebuild TV threads',
		'hint' => 'Rebuilds general show data, posters, credits and videos',
		'data-hide' => 'true',
		'_dependent' => array($__templater->formCheckBox(array(
		'name' => 'options[rebuildTypes]',
	), array(array(
		'value' => 'credits',
		'label' => 'Credits',
		'_type' => 'option',
	),
	array(
		'value' => 'videos',
		'label' => 'Videos',
		'_type' => 'option',
	)))),
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\TV:TvPostRebuild',
		'label' => 'Rebuild episode posts',
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\TV:TvCreditsRebuild',
		'label' => 'Rebuild credits',
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\TV:TvPersonsRebuild',
		'label' => 'Rebuild person data',
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\TV:TvVideoRebuild',
		'label' => 'Rebuild videos',
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\TV:TvWatchProviderRebuild',
		'label' => 'Rebuild watch providers',
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\TV:TvProductionCompaniesRebuild',
		'label' => 'Rebuild TV production companies',
		'_type' => 'option',
	)
,array(
		'value' => 'Snog\\TV:TvNetworksRebuild',
		'label' => 'Rebuild TV networks',
		'_type' => 'option',
	));
	if ($__templater->func('is_addon_active', array('ThemeHouse/Covers', ), false)) {
		$__compilerTemp1[] = array(
			'value' => 'Snog\\TV:TvThreadCoverUpdate',
			'label' => 'Rebuild thread covers',
			'_type' => 'option',
		);
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<h2 class="block-header">[OzzModz] TMDb TV Thread Starter</h2>
		<div class="block-body">
			' . $__templater->formRadioRow(array(
		'name' => 'job',
	), $__compilerTemp1, array(
	)) . '
			
			' . $__templater->formRow('
				' . $__templater->formRadio(array(
		'name' => 'job',
	), array(array(
		'value' => 'Snog\\TV:TvThreadImageDownload',
		'label' => 'Download thread poster images',
		'_type' => 'option',
	),
	array(
		'value' => 'Snog\\TV:TvForumImageDownload',
		'label' => 'Download forum poster images',
		'_type' => 'option',
	),
	array(
		'value' => 'Snog\\TV:TvPostImageDownload',
		'label' => 'Download episode images',
		'_type' => 'option',
	),
	array(
		'value' => 'Snog\\TV:TvPersonsImageDownload',
		'label' => 'Download person images',
		'_type' => 'option',
	),
	array(
		'value' => 'Snog\\TV:TvNetworkImageDownload',
		'label' => 'Download network images',
		'_type' => 'option',
	),
	array(
		'value' => 'Snog\\TV:TvCompanyImageDownload',
		'label' => 'Download company images',
		'_type' => 'option',
	))) . '
			', array(
		'label' => 'Local images',
		'explain' => 'Download images of episodes locally (ignoring the disabled option). Can be useful if you previously disabled local images but now want to load them. Does not check API for new posters, only downloads from a saved link.',
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