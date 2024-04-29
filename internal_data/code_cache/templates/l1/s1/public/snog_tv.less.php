<?php
// FROM HASH: 0cbd3d84ef25531d816493116b224e1b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '/* FIX FOR UiX2 STYLE PROBLEM */
.structItem .structItem-cell--main .structItem-minor {display: block;}

.tvhint{
	font-size: @xf-fontSizeSmaller;
	color: @xf-textColorMuted;
}

.message-tv {
	display: flex;
	width: 100%;
	padding-top: 10px;
	overflow:hidden;
	
	&.message-tv--reverse
	{
		flex-direction: row-reverse;
	}
	
	.message-tv-sidebar
	{
		flex: 1;
		width: 200px;
		margin: 0 15px 0 15px;
		float: left;
		text-align: center;
		vertical-align:top;

		.message-tv-sidebar-poster
		{
			padding-bottom: 10px;
			img {
				max-width: 185px;
			}
		}
	}
	
	.message-tv-main
	{
		width: 100%;
		
		.message--tv
		{
			height: auto;
			min-width: 200px;
			text-align:left;
			vertical-align:top;
			overflow:hidden;
			padding: 10px !important;
		}
	}
	
	.tv-providers 
	{
		.tv-provider img
		{
			height: @xf-snog_tv_watchProviderImgHeight;
			width: @xf-snog_tv_watchProviderImgWidth;
		}
	}

	.tv-companies 
	{
		.tv-company img
		{
			height: @xf-snog_tv_productionCompanyImgHeight;
			width: @xf-snog_tv_productionCompanyImgWidth;
		}
	}

	.tv-networks 
	{
		.tv-network img
		{
			height: @xf-snog_tv_networkImgHeight;
			width: @xf-snog_tv_networkImgWidth;
		}
	}
}

.episodeInputBlock {
	display: block;
	width: 100%;
	overflow: hidden;
}

.episodeBlockInput
{
	width:130px;
	margin-right:5px;
	float:left;
}



.messageEpisode {
	height:auto;
	min-width:128px;
	overflow: hidden;
	text-align:left;
	vertical-align:top;
	margin-bottom: 10px;
	border: 1px solid @xf-borderColorLight;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	padding: 10px !important;
}

.episodeImage
{
	width: @xf-snog_tv_episodeImageWidth;
	height: @xf-snog_tv_episodeImageHeight;
	float:left;
	display:block;
	vertical-align:middle;
	overflow:hidden;
	
	&--search {
		width: @xf-snog_tv_episodeSearchImageWidth;
		height: @xf-snog_tv_episodeSearchImageHeight;
	}
}

.episodeInfo {
	height:auto;
	width:auto;
	padding-left:10px;
	padding-top:5px;
	min-width:128px;
	text-align:left;
	vertical-align:bottom;
	overflow:hidden;
}

.episodePlot {
	height:auto;
	clear:both;
	min-width:128px;
	text-align:left;
	padding-top:10px;
	overflow:hidden;
}

.tvinfo {
	display:none;
}

.tvthreadlistMain {
	height: 133px;
}

.tvthreadlistInfo {
	vertical-align: middle !important;
}

.tvthreadlistReplies {
	padding-top:27px !important;
}

.TagPoster1 {
	padding-left:10px;
	width:190px;
	vertical-align:top;
	overflow:hidden;
}

.TagPoster1 img {
	max-width: 185px;
}

.controls.faint {
	text-align:right !important;
}

.tmdbnotice {
	width:100%;
	text-align:center;
	color: @xf-dimmedTextColor;
	font-size:10px;
}

.structItem-cell {
	&.structItem-cell--tv
	{
		text-align: center;
		width: 92px !important;
		position: relative;
	}
	
	.structItem-secondaryIcon
	{
		position: absolute;
		right: +10px !important;
		bottom: -5px;
	}
}

.tvPoster {
	margin-top:5px;
	width: @xf-snog_tv_posterImageWidth;
	height: @xf-snog_tv_posterImageHeight;
	position: relative;
}

.tvnode-poster
{
	display: table-cell;
	vertical-align: middle;
	text-align: center;
	width: 87px;
	padding: @xf-paddingLarge 0 @xf-paddingMedium @xf-paddingLarge;
}

.tvblock-poster {
	display: block;
	float: left;
	width: 110px;
	height: 155px;
	padding: 10px 0 5px 10px;
	overflow:hidden;
}

.tvblockinfo {
	display: block;
	width: calc(~\'100% - 110px\');
	float:left;
	padding: 10px 10px 5px 10px;
	font-size: @xf-fontSizeSmall;
	color: @xf-textColor;
}

.tvExplain
{
	display: block;
	font-style: normal;
	.xf-formExplain();

	.m-textColoredLinks();
}

.message-tvUserInfo
{
	.xf-messageUserBlock(no-border);
	border-top: @xf-messageUserBlock--border-width solid @xf-messageUserBlock--border-color;

	&:last-child
	{
		.m-borderBottomRadius(@block-borderRadius-inner);
	}

	.contentRow-figure
	{
		width: @xf-messageUserBlockWidth;
		text-align: center;
	}

	.contentRow-main
	{
		padding-left: 2 * (@xf-messagePadding);
		text-align: left;
	}

	@media (max-width: @xf-messageSingleColumnWidth)
	{
		.contentRow-figure
		{
			width: auto;
		}

		.contentRow-main
		{
			padding-left: @xf-paddingLarge;
		}
	}
}';
	return $__finalCompiled;
}
);