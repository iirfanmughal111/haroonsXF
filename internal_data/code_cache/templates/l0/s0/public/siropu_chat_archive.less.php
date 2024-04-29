<?php
// FROM HASH: 32c2c9e9475145415a5d3595f4e3aaf5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '#siropuChatArchive
{
	.username
	{
		font-weight: bold;
	}
	.bbWrapper
	{
		display: inline;
	}
	.fa
	{
		font-size: @xf-siropuChatIconFontSize;
	}
}

@media (max-width: @xf-responsiveNarrow)
{
	#siropuChatArchive
	{
		.inputGroup--auto
		{
			flex-wrap: wrap;

			> *
			{
				margin-bottom: 3px;
			}
			button[type="submit"]
			{
				width: 100%;
			}
		}
		.inputGroup-splitter
		{
			display: none;
		}
	}
}';
	return $__finalCompiled;
}
);