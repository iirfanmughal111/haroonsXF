<?php
// FROM HASH: c41d67fdadf1260ab2f9bd934b58bc2e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.media
{
	img
	{
		max-width: 100%;
		max-height: 70vh;
	}
}

.editImagePreview
{
	img
	{
		max-height: 300px;
	}
}

.button
{
	&.button--icon
	{
		&--move { .m-buttonIcon(@fa-var-arrows, 1em); }
		&--crop { .m-buttonIcon(@fa-var-crop, 1em); }

		&--zoom-in { .m-buttonIcon(@fa-var-search-plus, 1em); }
		&--zoom-out { .m-buttonIcon(@fa-var-search-minus, 1em); }

		&--rotate-left { .m-buttonIcon(@fa-var-undo, 1em); }
		&--rotate-right { .m-buttonIcon(@fa-var-redo, 1em); }

		&--flip-h { .m-buttonIcon(@fa-var-arrows-h, 1em); }
		&--flip-v { .m-buttonIcon(@fa-var-arrows-v, 1em); }
	}
}

' . $__templater->includeTemplate('xfmg_cropper.less', $__vars);
	return $__finalCompiled;
}
);