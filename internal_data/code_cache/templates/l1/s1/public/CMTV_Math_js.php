<?php
// FROM HASH: 20907afa386f8773a490f5d97cf6c6fd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<script>
	const CMTV_MATH_RENDER_OPTIONS =
	{
		"delimiters":
		[
			{left: "[imath]", right: "[/imath]", display: false},
			{left: "[math]",  right: "[/math]", display: true}
		].concat(' . $__templater->filter($__vars['xf']['options']['CMTV_Math_customMathDelimiters'], array(array('raw', array()),), true) . '),

		"ignoredClasses": [].concat(' . $__templater->filter($__vars['xf']['options']['CMTV_Math_ignoredClasses'], array(array('raw', array()),), true) . '),

		"macros": ' . ($__vars['xf']['options']['CMTV_Math_macros'] ? $__templater->filter($__vars['xf']['options']['CMTV_Math_macros'], array(array('raw', array()),), true) : '{}') . '
	};
	
	(function ($, document)
	{
		$(document).on(\'xf:reinit\', function (e)
		{
			renderMathInElement(document.body, CMTV_MATH_RENDER_OPTIONS);
		});
	})
	(jQuery, document);
</script>';
	return $__finalCompiled;
}
);