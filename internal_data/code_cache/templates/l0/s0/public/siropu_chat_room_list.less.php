<?php
// FROM HASH: 9e77d6b34525384447796d559f423832
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '#siropuChatRooms
{
	> li[data-room-id]
	{
		display: table;
		table-layout: fixed;
		width: 100%;
		.xf-siropuChatRoomListItem();

		&:last-child
		{
			margin-bottom: 0;
		}
		> div
		{
			display: table-cell;
			vertical-align: middle;
		}
	}
}
#siropuChatRoomListOptions
{
	position: sticky;
	top: 0;
	display: inline-block;
	margin-bottom: 10px;
	z-index: 10;

	a
	{
		margin-right: 5px;
	}
	input
	{
		display: inline-block;
		vertical-align: middle;
		width: auto;
		padding: 3px 7px;
	}
}
.siropuChatRoomInfo
{
	width: 60%;
	vertical-align: top;

	h3
	{
		margin: 0;
		display: initial;
		color: @xf-textColorDimmed;
		cursor: pointer;
	}
	div
	{
		color: @xf-textColorMuted;
	}
	.siropuChatRoomSanctionNotice
	{
		color: crimson;
	}
	.username
	{
		font-weight: normal !important;
	}
}
div.siropuChatRoomDetails,
div.siropuChatRoomUsers
{
	display: inline-block;
	font-size: 12px;
	font-style: italic;
}
div.siropuChatRoomDetails
{
	&:after
	{
		content: " | ";
	}
}
div.siropuChatRoomUsers
{
	ul
	{
		display: inline-block;
		margin: 0;
		padding: 0;

		li
		{
			display: inline-block;

			&:after
			{
				content: ", ";
			}
			&:last-child:after
			{
				content: "";
			}
		}
	}
}
.siropuChatRoomManage
{
	width: 20%;
	text-align: right;
	font-size: 16px;

	span
	{
		display: none;
		font-size: 14px;
		margin-left: 5px;
	}
}
.siropuChatRoomAction
{
	width: 20%;
	text-align: right;
	position: relative;
}
.siropuChatRoomPassword
{
	width: 170px;
	position: absolute;
	right: 100px;
	padding: 5px 10px;
	display: none;
}
.chatResponsive()
{
	#siropuChatRooms
	{
		> li[data-room-id]
		{
			display: block;
			padding-bottom: 20px;
			border-bottom: 1px solid #eee;
			
			> div
			{
				display: block;
				width: auto;
			}
			&:last-child
			{
				border-bottom: 0;
				padding-bottom: 0;
			}
		}
	}
	div.siropuChatRoomDetails
	{
		&:after
		{
			content: "";
		}
	}
	div.siropuChatRoomUsers
	{
		display: block;
	}
	div.siropuChatRoomManage
	{
		text-align: center;
		margin: 10px 0;

		span
		{
			display: initial;
		}
	}
	div.siropuChatRoomAction
	{
		.button
		{
			display: block;
			width: 100%;
		}
	}
}
@media (max-width: @xf-responsiveNarrow)
{
	.chatResponsive();
}
#siropuChat.siropuChatSidebar
{
	.chatResponsive();
}';
	return $__finalCompiled;
}
);