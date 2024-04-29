<?php
// FROM HASH: 48e97b91086268b2f73c4a1485c2ae9c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '//
// Katex settings
//

 .katex-display
{
	margin: 0 !important;
	.base { margin: 15px/2.2 0; }
	
	overflow: hidden;
    overflow-x: auto;

	&::-webkit-scrollbar
	{
		height: 5px;
	}

	&::-webkit-scrollbar-track
	{
		background: @xf-contentAltBg;
		border-radius: @xf-borderRadiusSmall;
	}

	&::-webkit-scrollbar-thumb
	{
		background: @xf-borderColor;
		border-radius: @xf-borderRadiusSmall;

		&:hover
		{
			background: @xf-borderColorHeavy;
		}
	}
}

.katex > .katex-html
{
	white-space: normal;
}


//
// "Insert math" dialog
//

._fadeInOut()
{
	opacity: 0;
	transition: opacity .2s;
	&.showing
	{
		opacity: 1;
	}
}

#editor_math_form
{	
	.preview-loading,
	.preview-container
	{
		._fadeInOut();
	}
	
	.preview-loading
	{
		margin-right: 5px;
	}
	
	.preview-container
	{
		display: flex;
		align-items: center;
		justify-content: center;
		
		min-height: 100px;
		
		.empty, .error
		{
			font-size: 120%;
			color: @xf-textColorMuted;
		}
		
		.error
		{
			color: @xf-errorColor;
		}
		
		.preview
		{
			//overflow: auto;
		}
	}
}';
	return $__finalCompiled;
}
);