<?php
// FROM HASH: f8e7d4845559b2ab01ca12b7d2dea02a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.siropuChatNoSelect()
{
	webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}
.siropuChatFixedPosition(@bottom)
{
	position: fixed;
	left: 0;
	right: 0;
	bottom: @bottom;
	margin: 0 auto;
	max-width: @xf-pageWidthMax - 20;
	z-index: 500;
}
#siropuChat
{
	.block-container
	{
		.xf-siropuChatContainer();
	}
	a[role="button"],
	{
		cursor: pointer;
		text-decoration: none;
		.siropuChatNoSelect();
	}
	&.siropuChatAllPages
	{
		display: none;
		.siropuChatFixedPosition(50px);
	}
	&.siropuChatBelowContent
	{
		margin-top: 20px;
	}
	&:not(.siropuChatEditorTop)
	{
		.fr-dropdown-menu, .fr-popup
		{
			top: initial !important;
			bottom: 32px !important;
		}
		.fr-popup .fr-arrow
		{
			display: none;
		}
	}
	.bbWrapper
	{
		display: inline;
	}
	.fa
	{
		font-size: @xf-siropuChatIconFontSize;
	}
	.fa-book
	{
		color: @xf-siropuChatRulesIconColor;
	}
	.fa-life-ring
	{
		color: @xf-siropuChatHelpIconColor;
	}
	.fa-users
	{
		color: @xf-siropuChatUsersIconColor;
	}
	.fa-cogs
	{
		color: @xf-siropuChatSettingsIconColor;
	}
	.fa-bars
	{
		color: @xf-siropuChatOptionsIconColor;
	}
	.fr-box.fr-basic
	{
		border: 0;

		.fr-element
		{
			padding-bottom: 0;
		}
	}
}
#siropuChatHeader.block-header a
{
	-webkit-text-fill-color: unset;
}
#siropuChatSettings
{
	width: 250px;

	fieldset
	{
		margin: 0 0 10px 0;
		padding: 0;
		border: 0;
	}
	fieldset:last-child
	{
		margin: 0;
	}
	legend
	{
		font-weight: bold;
	}
	.inputChoices > li
	{
		margin: 0;

		input
		{
			top: 1px;
		}
	}
	.inputGroup.inputGroup--color
	{
		width: auto;
	}
	> ul
	{
		padding: 10px;
	}
}
#siropuChatOptions
{
	float: right;
	margin-top: 5px;
	font-size: 14px;

	> a
	{
		padding: 0 2px;
		text-decoration: none;

		span
		{
			display: none;
		}
		i
		{
			&:hover
			{
				font-weight: bold;
			}
		}
	}
}
#siropuChatToggleUsers
{
	display: none;
}
#siropuChatNotice
{
	padding: 10px;
	border-bottom: 1px solid @xf-borderColor;
	color: @xf-textColorDimmed;

	> i
	{
		font-weight: bold;
	}
	> span
	{
		font-size: 12px;
	}
	> a
	{
		font-size: 10px;
	}
}
#siropuChatTabs
{
	display: flex;
	flex-wrap: wrap;
	align-items: center;

	.xf-siropuChatTabsContainer();

	a
	{
		.xf-siropuChatTabsItem();

		&:hover
		{
			.xf-siropuChatTabsItemHover();
		}
		&.siropuChatActiveTab
		{
			.xf-siropuChatTabsItemActive();
		}
		> span
		{
			.xf-siropuChatTabsItemBadge();

			&.siropuChatTabCountActive
			{
				background: @xf-siropuChatActiveUserCountBadgeColor;
				color: @xf-siropuChatActiveUserCountColor;
			}
			&.siropuChatTabCountInactive
			{
				background: @xf-siropuChatNoUserCountBadgeColor;
				color: @xf-siropuChatNoUserCountColor;
			}
		}
		&[data-target="room"]
		{
			margin-right: 10px;
		}
		&[data-target="conv-list"]
		{
			margin-right: 5px;
		}
	}
	> span
	{
		margin-left: auto;
		padding: 9px 0;
	}
}
#siropuChatContent
{
	height: @xf-siropuChatContentHeight;

	> *
	{
		display: none;
	}
	ul
	{
		list-style-type: none;
	}
	.username
	{
		font-weight: bold;
	}
	.fa-thumbs-up, a.reaction
	{
		color: @xf-siropuChatLikeIconColor;
	}
	.fa-thumbs-o-down
	{
		color: @xf-siropuChatUnlikeIconColor;
	}
	.fa-quote-right
	{
		color: @xf-siropuChatQuoteIconColor;
	}
	.fa-link
	{
		color: @xf-siropuChatLinkIconColor;
	}
	.fa-flag
	{
		color: @xf-siropuChatReportIconColor;
	}
	.fa-edit
	{
		color: @xf-siropuChatEditIconColor;
	}
	.fa-trash
	{
		color: @xf-siropuChatDeleteIconColor;
	}
	.fa-ban
	{
		color: @xf-siropuChatSanctionIconColor;
	}
	';
	if ($__vars['xf']['options']['siropuChatEnableWhosTyping']['room'] OR $__vars['xf']['options']['siropuChatEnableWhosTyping']['conv']) {
		$__finalCompiled .= '
		position: relative;
	';
	}
	$__finalCompiled .= '
}
.siropuChatPage
{
	#siropuChatContent
	{
		height: @xf-siropuChatPageContentHeight;
	}
}
.siropuChatMaximized
{
	#siropuChatContent
	{
		height: @xf-siropuChatMaximizedContentHeight;
	}
}
.siropuChatMessages
{
	margin-right: 300px;

	> li
	{
		display: table;
		table-layout: fixed;
		width: 100%;
		margin-bottom: 5px;
		position: relative;

		&:last-child
		{
			margin-bottom: 0;
		}
		> div
		{
			display: table-cell;
			vertical-align: top;
		}
		&.siropuChatError
		{
			font-style: italic;
		}
	}
	.bbWrapper
	{
		display: inline;
	}
	.reactionsBar-link
	{
		font-size: 10px;
	}
}
.siropuChatUsers
{
	width: 250px;
	float: right;
	border-left: 1px solid @xf-borderColor;

	> li
	{
		margin-bottom: 5px;

		&:last-child
		{
			margin-bottom: 0;
		}
	}
}
.siropuChatUserMenu
{
	form
	{
		display: none;
		padding: 0 10px;

		.button
		{
			padding-left: 0;
    		padding-right: 0;
			display: block;
			width: 100%;
		}
	}
}
.siropuChatUserStatus
{
	.xf-siropuChatUserStatus();

	&:before
	{
		content: \'\\21B3\';
		margin-left: 10px;
	}
}
.siropuChatMessages,
.siropuChatUsers,
#siropuChatRooms
{
	height: 100%;
	overflow: auto;
	padding: 10px;
	margin: 0;
}
#siropuChatNoRoomsJoined
{
	margin: 0;
	padding: 10px;
}
.siropuChatConversation.siropuChatUsers
{
	padding: 0 !important;

	> li[data-conv-id]
	{
		.xf-siropuChatConversationListItem();

		&:hover
		{
			.xf-siropuChatConversationListItemHover();
			cursor: pointer;
		}
		&.siropuChatActiveConversation
		{
			.xf-siropuChatConversationListItemActive();
		}
	}
}
#siropuChatNoConversations
{
	padding: 10px;
}
#siropuChatStartConversation
{
	form
	{
		width: 50%;
		border: 0;
		float: right;
		padding: 10px;
	}
}
.siropuChatConversationActions
{
	float: right;
	display: none;

	a
	{
		font-size: 18px;
		color: @xf-textColorMuted;
	}
}
.siropuChatLeaveConversation:hover
{
	color: crimson;
}
.siropuChatConversationPopup:hover
{
	color: @xf-linkColor;
}
.siropuChatTag
{
	color: @xf-textColorDimmed;

	&:hover
	{
		color: @xf-textColor;
	}
}
.siropuChatMessageLikes
{
	font-weight: bold;
	font-size: 12px;
	color: green;
}
.siropuChatMessageEdited
{
	float: right;
	font-size: 12px;
	font-style: italic;
	color: @xf-textColorMuted;

	&span
	{
		cursor: help;
	}
}
.siropuChatRecipients
{
	color: @xf-textColorMuted;
	cursor: pointer;
}
.siropuChatDateTime
{
	.xf-siropuChatDateTime();
}
.siropuChatMention
{
	.xf-siropuChatUserMention();

	.siropuChatDateTime
	{
		.xf-siropuChatUserMentionDate();
	}
}
.siropuChatTarget
{
	background-color: #f0e68c;
}
.siropuChatLoadMoreMessages
{
	text-align: center;

	a
	{
		display: block;
		font-weight: bold;
		font-size: 12px;
		
		&[data-complete="true"]
		{
			color: crimson;
		}
	}
}
.siropuChatMessageText
{
	.xf-siropuChatMessageText();

	.lbContainer
	{
		display: block;
		margin-top: 5px;
	}
	.bbMediaWrapper
	{
		margin-top: 5px;
	}
	img
	{
		max-height: 250px;
	}
}
.siropuChatMessageContentRight, 
{
	text-align: right;
	width: 150px;
}
.siropuChatMessageContentLeft
{
	.avatar
	{
		vertical-align: middle;
	}
}
.siropuChatMessageActions
{
	display: none;
	position: absolute;
	right: 0;

	a
	{
		span
		{
			display: none;
		}
		&:hover
		{
			text-decoration: none;
		}
	}
}
.siropuChatNewMessage
{
	animation: blink 1s steps(5, start) infinite;
	-webkit-animation: blink 1s steps(5, start) infinite;

	&:hover
	{
		-webkit-animation-play-state: paused;
		-moz-animation-play-state: paused;
		-o-animation-play-state: paused;
		animation-play-state: paused;
	}
}
.siropuChatGuest
{
	color: @xf-textColorDimmed;
}
#siropuChatEditor
{
	dd
	{
		padding: 0;
	}
	.fr-toolbar
	{
		.xf-siropuChatEditorToolbar();
	}
	.fr-wrapper
	{
		.xf-siropuChatEditorInput();
	}
	.fr-element img
	{
		max-height: 100px;
	}
	.fr-view.fr-disabled
	{
		z-index: 0;
	}
	.editorSmilies
	{
		border-color: @xf-borderColor;
		border-left: 0;
		border-right: 0;
		max-height: 100px;
    	overflow: auto;
	}
	form
	{
		position: relative;

		button[type="submit"]
		{
			position: absolute;
			bottom: 5px;
			right: 5px;
			z-index: 1;
		}
	}
}
.siropuChatEditorTop
{
	#siropuChatTabs
	{
		border-bottom: 0;
	}
	#siropuChatEditor
	{
		.fr-wrapper
		{
			border-width: 0 0 1px 0;
			border-color: @xf-borderColor;
		}
	}
	.siropuChatAds[data-position="belowEditor"]
	{
		border-top: 0;
		border-bottom: 1px solid @xf-borderColor;
	}
}
.siropuChatAds
{
	padding: 10px;
	margin: 0;

	&[data-position="aboveEditor"]
	{
		border-top: 1px solid @xf-borderColor;
	}
	&[data-position="belowEditor"]
	{
		border-top: 1px solid @xf-borderColor;
	}
}
#siropuChatBar.block-container
{
	.siropuChatFixedPosition(0);
	.xf-siropuChatBarContainer();

	display: flex;
	justify-content: flex-end;
	cursor: pointer;

	.username
	{
		font-weight: bold;
	}
}
#siropuChatBarMessageContainer
{
	padding: 10px;
	flex: 1;

	&:before
	{
		content: "\\f4a6";
		font-family: "Font Awesome 5 Pro";
		margin-right: 5px;
	}
}
#siropuChatBarUserCount
{
	float: right;
	border-left: 1px solid @xf-borderColor;
	.siropuChatNoSelect();

	a
	{
		display: block;
		height: 100%;
		.xf-siropuChatBarButton();

		span
		{
			color: @xf-textColorMuted;
			font-weight: normal;

			&:after
			{
				content: "]";
			}
			&:before
			{
				content: "[";
			}
		}
	}
}
#siropuChatBarDisable
{
	margin: 5px 10px 0 0;
	font-size: 25px;
	color: @xf-textColorDimmed;

	&:hover
	{
		color: crimson;
	}
}
#siropuChatPopup
{
	cursor: pointer;
}
.siropuChatPage
{
	.siropuChatUsers
	{
		width: 300px;
	}
}
.siropuChatHideUserList
{
	.siropuChatRoom.siropuChatUsers
	{
		display: none !important;
	}
}
.siropuChatMessageRow.siropuChatBot ol li b
{
	cursor: pointer;
}
#siropuChatFullPage
{
	&::-webkit-scrollbar
	{
    	display: none;
	}
	.siropuChatUsers
	{
		width: 250px;
	}
	#siropuChat
	{
		margin: 0;
	}
}
#siropuChatWhosTyping
{
	.xf-siropuChatWhosTypingContainer();
}
.chatResponsive()
{
	#siropuChatTabs
	{
		display: block;
		text-align: center;
		padding: 5px 0;

		 > span
		{
			float: none;
			display: block;
			border-top: 1px solid @xf-borderColor;
			margin-top: 5px;
			padding-top: 5px;

			.siropuChatNoRooms &
			{
				border-top: 0;
			}
		}
	}
	#siropuChatToggleUsers
	{
		display: initial;
	}
	.siropuChatMessages,
	.siropuChatUsers,
	#siropuChatStartConversation form
	{
		width: auto !important;
		float: none;
	}
	.siropuChatMessages > li
	{
		display: block;
		border-bottom: 1px solid @xf-borderColorFaint;
		margin-bottom: 0;
		padding: 10px 0;

		&:last-child
		{
			border-bottom: 0;
		}
		> div
		{
			display: block;
		}
	}
	.siropuChatMessageContentRight
	{
		text-align: center;
		width: auto;
	}
	.siropuChatMessageActions
	{
		position: initial;

		a span
		{
			display: initial;
			font-size: 12px;
		}
	}
	#siropuChatEditor
	{
		.button-text span
		{
			display: none;
		}
	}
	#siropuChatWhosTyping
	{
		display: block;
	}
}
@media (max-width: @xf-responsiveNarrow)
{
	.chatResponsive();

	#siropuChatContent
	{
		height: @xf-siropuChatContentHeightMobile;
	}
	#siropuChat.siropuChatAllPages
	{
		bottom: 35px;
	}
	#siropuChatOptions
	{
		float: right;
		margin-top: 5px;
		font-size: 14px;

		> a
		{
			padding: 0 4px;
		}
	}
	#siropuChatSettings
	{
		.menu--potentialFixed &
		{
			max-height: 300px;
		}
	}
	#siropuChatBar.block-container
	{
		border-width: 0;
		display: block;
	}
	#siropuChatBarMessageContainer
	{
		padding: 5px;
	}
	#siropuChatBarUserCount
	{
		float: none;
		text-align: center;
		border-left: 0;
		border-top: 1px solid @xf-borderColor;

		> a
		{
			padding: 5px;
		}
	}
	#siropuChatBarDisable
	{
		position: absolute;
		right: 10px;
		margin: 0;
	}
}
@media (max-width: @xf-responsiveWide)
{
	#siropuChatToggleUsers
	{
		display: initial;
	}
}
#siropuChat.siropuChatSidebar
{
	.chatResponsive();
}
@keyframes blink
{
	to
	{
		visibility: hidden;
	}
}
@-webkit-keyframes blink
{
	to
	{
		visibility: hidden;
	}
}

@-moz-document url-prefix()
{ 
	.siropuChatMessages > li:last-child
	{
		margin-bottom: 5px;
	}
}
.siropuChatAnnouncement
{
	.xf-siropuChatAnnouncement();
}
.siropuChatNotRecipient
{
	color: crimson;

	&:before
	{
		content: \'\\f30d\';
		font-family: "Font Awesome 5 Pro";
		margin-right: 5px;
	}
}
#siropuChat button[data-cmd="xfCustom_chat"]
{
	display: initial !important;
}
' . $__templater->includeTemplate('siropu_chat_activity_status.less', $__vars) . '
' . $__templater->includeTemplate('siropu_chat_custom.less', $__vars) . '
' . $__templater->includeTemplate('bb_code.less', $__vars);
	return $__finalCompiled;
}
);