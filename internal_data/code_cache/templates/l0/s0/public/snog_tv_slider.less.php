<?php
// FROM HASH: d7050db423cd47df1cebdf717e550fd2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.carousel-tvPosterSlider
{
	.carousel-item-simple
	{
		border-radius: @xf-blockBorderRadius;
		width: 100%;
		min-width: 0;
		margin: 0 auto;
	}
	
	.carousel-item-image
	{ 
		display: flex;
		flex-flow: row nowrap;
		align-items: flex-end;
		justify-content: center;
		position: relative;
		text-align: center;
		word-break: break-word;
		color: rgb(255, 255, 255);

		.carousel-item-caption
		{
			position: absolute;
			margin: 0;
			word-break: break-word;
			background: rgba(0, 0, 0, 0.5);
			height: 2.5em;
			width: 100%;
			line-height: 2.5;
		}
	}
	
	.carousel-item-label
	{
		position: absolute;
		top: 0;
		word-break: break-word;
		background: rgba(0, 0, 0, 0.5);
		right: 0;
		margin: 10px;
		padding: 5px;

	}
}';
	return $__finalCompiled;
}
);