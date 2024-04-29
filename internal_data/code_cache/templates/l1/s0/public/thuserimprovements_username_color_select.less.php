<?php
// FROM HASH: cb97cff430fe92bebcb815278f16dda8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '#th_name_color_selector .inputChoices {
	width: 224px;
}

#th_name_color_selector .inputChoices-choice {
	display: inline-block;
    margin: 0 -4.1px 0 0;
    padding: 0;
}

#th_name_color_selector .iconic.iconic--radio.iconic--labelled {
	text-indent: -9999;
	font-size: 0;
}

#th_name_color_selector label.iconic > input[type="radio"] + i {
	position: static;
	text-align: center;
    line-height: 32px;
    height: 32px;
    width: 32px;
    font-size: 13px;
	cursor: pointer;
	display: block;
	position: relative;
}
#th_name_color_selector label.iconic > input[type="radio"] + i:hover,
#th_name_color_selector label.iconic > input[type="radio"]:active + i,
#th_name_color_selector label.iconic > input[type="radio"]:focus + i {
	outline: 1px solid #141414;
	z-index: 2;
}

#th_name_color_selector label.iconic > input[type="radio"] + i:before {
	content: " ";
}
#th_name_color_selector label.iconic > input[type="radio"]:checked + i:before {
	content: "\\f00c";
	color: #FFF;
}

#th_name_color_selector label.iconic > input[type="radio"][value="0"] + i:before {
	content: "\\f12d";
	color: @xf-textColor;
}


';
	$__compilerTemp1 = $__templater->func('range', array(1, 27, ), false);
	if ($__templater->isTraversable($__compilerTemp1)) {
		foreach ($__compilerTemp1 AS $__vars['x']) {
			$__finalCompiled .= '
#th_name_color_selector label.iconic > input[type="radio"][value="' . $__templater->escape($__vars['x']) . '"] + i {
	background-color: @xf-klUIUsernameColor' . $__templater->escape($__vars['x']) . ';
}
';
		}
	}
	return $__finalCompiled;
}
);