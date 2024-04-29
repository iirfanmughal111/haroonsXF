<?php
// FROM HASH: 6644078c7c20e2f7cfd3d4e60384e4b1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.siropuChatWidget
{
	> ul
	{
		max-height: 325px;
		overflow: auto;
	}
	ul
	{
		padding: 0;
		margin: 0;
		list-style-type: none;
	}
	> li
	{
		margin-bottom: 5px;

		&:last-child
		{
			margin-bottom: 0;
		}
	}
}
.siropuChatWidgetRooms > ul
{
	> li
	{
		position: relative;
		padding: 5px 10px;
		margin: 0;
		border-bottom: 1px solid #eee;
		cursor: pointer;

		&:first-child
		{
			border-top: 1px solid #eee;
		}
		&:hover
		{
			background: #f7f7f7;
		}
		> span
		{
			float: right;
			font-weight: bold;
			position: absolute;
    		right: 10px;
		}
		&.siropuChatActiveRoom > span
		{
			color: green;
		}
		&.siropuChatInactiveRoom > span
		{
			color: lightgrey;
		}
		> a
		{
			display: none;
			position: absolute;
			right: 10px;
    		top: 0
		}
		ul
		{
			display: none;
			margin-top: 10px;
		}
	}
}
.siropuChatWidgetGrid
{
	> li
	{
		display: inline-block;
		margin-right: 1px;
		margin-top: 1px;

		&:last-child
		{
			margin-right: 0;
		}
	}
}
.siropuChatFindItem
{
	margin-bottom: 10px;
}
.siropuChatUserMessageCount
{
	font-weight: bold;
	color: @xf-textColorMuted;
	float: right;
}
' . $__templater->includeTemplate('siropu_chat_activity_status.less', $__vars);
	return $__finalCompiled;
}
);