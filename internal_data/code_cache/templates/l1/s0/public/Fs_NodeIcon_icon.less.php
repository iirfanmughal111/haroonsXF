<?php
// FROM HASH: a6901a598626e0fb81a5740b1979809f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.node-icon--custom
{
	display: table-cell;
	vertical-align: middle;
	text-align: center;
	padding: @xf-paddingLarge 0 @xf-paddingLarge @xf-paddingLarge;
	background-size: 60% 60%;
	background-repeat: no-repeat;
	background-position: center;
}

.subNodeMenu .subNodeLink.subNodeLink--custom
{
	&::before
	{
		display: none;
	}
	
	.subNodeLink--icon
	{
		display: inline-block;
		width: 1em;
		height: 1em;
		padding-right: .3em;
		text-decoration: none;
		background-size: cover;
		background-position: center;
		vertical-align: middle;
	}
}';
	return $__finalCompiled;
}
);