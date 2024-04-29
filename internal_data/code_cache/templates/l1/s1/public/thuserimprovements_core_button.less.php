<?php
// FROM HASH: 36c2bed08f6a61302996c9c4f1e1bff9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ############################ KL/UI BUTTONS #################

.button,
a.button // needed for specificity over a:link
{
	&.button--provider
	{
		&--th_dropbox
		{
			.m-buttonColorVariation(#007ee5, white);
			.m-buttonIcon(@fa-var-dropbox, .58em);
		}
		&--th_deviantart
		{
			.m-buttonColorVariation(#05CC47, white);
			.m-buttonIcon(@fa-var-deviantart, .58em);
		}
		&--th_amazon
		{
			.m-buttonColorVariation(#fad776, black);
			.m-buttonIcon(@fa-var-amazon, .58em);
		}
		&--th_reddit
		{
			.m-buttonColorVariation(#FF5700, white);
			.m-buttonIcon(@fa-var-reddit-alien, .58em);
		}
		&--th_twitch
		{
			.m-buttonColorVariation(#6441A4, white);
			.m-buttonIcon(@fa-var-twitch, .58em);
		}
		&--th_instagram
		{
			.m-buttonColorVariation(#cd486b, white);
			.m-buttonIcon(@fa-var-instagram, .58em);
		}
		&--th_pinterest
		{
			.m-buttonColorVariation(#BD081C, #f6f5f3);
			.m-buttonIcon(@fa-var-pinterest-p, .58em);
		}
		&--th_battlenet
		{
			.m-buttonColorVariation(#01a8d8, white);
			.button-text::before {
				background: url(styles/themehouse/userimprovements/logo-battlenet.svg);
				width: 17px;
				height: 20px;
				background-size: 20px 20px;
				background-position: center;
				content: \' \';
				display: inline-block;
				margin-left: -3px;
				filter: invert(100%);
				margin-top: -8px;
				margin-right: 0;
			}
		}
		&--th_discord
		{
			.m-buttonColorVariation(#7289DA, white);
			.button-text::before {
				background: url(styles/themehouse/userimprovements/logo-discord.svg);
				width: 17px;
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
}';
	return $__finalCompiled;
}
);