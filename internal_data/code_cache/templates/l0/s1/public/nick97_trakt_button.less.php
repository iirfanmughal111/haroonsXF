<?php
// FROM HASH: ded88a1798bc3363ed61749631cca1de
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.button,
a.button // needed for specificity over a:link
{
	&.button--provider
	{
		&--nick_trakt
		{
			.m-buttonColorVariation(#7289DA, white);
			.button-text::before {
				background: url(styles/nick97/TraktLogo/trakt-icon-red.svg);
				width: 20px;
				height: 20px;
				background-size: 20px 20px;
				background-position: center;
				content: \' \';
				display: inline-block;
				margin-left: -3px;
				margin-top: -8px;
				margin-right: 0;
			}
		}
	}
}

a.button.button--provider--nick_trakt{
	background-color: #000;	
	border-color: #000000 #000000 #000000 #000000;
}

a.button.button--provider--nick_trakt:hover{
	background-color: #000;
	border-color: #000000 #000000 #000000 #000000;
}

a.button.button--provider--nick_trakt:focus{
	background-color: #000;
	border-color: #000000 #000000 #000000 #000000;
}

a.button.button--provider--nick_trakt:active{
	background-color: #000;
	border-color: #000000 #000000 #000000 #000000;
}';
	return $__finalCompiled;
}
);