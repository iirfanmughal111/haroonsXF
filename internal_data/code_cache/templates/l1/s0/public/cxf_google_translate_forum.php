<?php
// FROM HASH: 98d65d833daddd0fb477c6d9031c2ef7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('cxf_google_translate_forum.less');
	$__finalCompiled .= '
<div id="google_translate_element"></div>

<script type="text/javascript">
function googleTranslateElementInit() {
	new google.translate.TranslateElement({pageLanguage: \'en\'';
	if ($__templater->func('property', array('gtf_options', ), false) == 'SIMPLE') {
		$__finalCompiled .= ', layout: google.translate.TranslateElement.InlineLayout.SIMPLE';
	} else if ($__templater->func('property', array('gtf_options', ), false) == 'HORIZONTAL') {
		$__finalCompiled .= ', layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL';
	} else if ($__templater->func('property', array('gtf_options', ), false) == 'VERTICAL') {
	}
	$__finalCompiled .= '}, \'google_translate_element\');
}
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>';
	return $__finalCompiled;
}
);