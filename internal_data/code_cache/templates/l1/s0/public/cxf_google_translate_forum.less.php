<?php
// FROM HASH: 4b76b45b1791661a503a9d6f069ec619
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '#google_translate_element {
	.xf-gtf_style();
}

.goog-te-gadget {
	';
	if ($__templater->func('property', array('gtf_float', ), false) == 'left') {
		$__finalCompiled .= '
	float: left;
	';
	} else if ($__templater->func('property', array('gtf_float', ), false) == 'right') {
		$__finalCompiled .= '
	float: right;
	';
	} else {
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '
}

.goog-te-banner-frame {
	z-index: 0 !important;
}';
	return $__finalCompiled;
}
);