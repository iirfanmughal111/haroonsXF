<?php
// FROM HASH: c4ce28dc6a6b3fcb9b555aa87a79ddd5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('snog_tv.less');
	$__finalCompiled .= '
<br />
<div class="episodeInputBlock">
<span class="tvExplain">' . 'If you wish to post an episode for this TV show, enter the Season and Episode numbers' . ':</span>
	<div class="episodeBlockInput">
		' . $__templater->formTextBox(array(
		'name' => 'season',
		'placeholder' => 'Season number',
		'autosize' => 'true',
		'size' => '10',
		'maxlength' => '10',
	)) . '	
	</div>
	<div class="episodeBlockInput">
		' . $__templater->formTextBox(array(
		'name' => 'episode',
		'placeholder' => 'Episode number',
		'autosize' => 'true',
		'size' => '10',
		'maxlength' => '10',
	)) . '
	</div>
</div>';
	return $__finalCompiled;
}
);