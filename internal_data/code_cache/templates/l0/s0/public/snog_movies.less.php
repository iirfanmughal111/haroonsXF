<?php
// FROM HASH: 7b1c14ce2dd08585a413c7a0f4302ce6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '/* FIX FOR UiX2 STYLE PROBLEM */
.structItem .structItem-cell--main .structItem-minor {display: block;}

.structItem-movie
{
	font-size: @xf-fontSizeSmaller;
	color: @xf-textColorMuted;

	.m-hiddenLinks();
}

.moviehint{
	font-size: @xf-fontSizeSmaller;
	color: @xf-textColorMuted;
}

.message-movie
{
	display: flex;
	width: 100%;
	padding-top: 10px;
	overflow:hidden;
	
	&.message-movie--reverse
	{
		flex-direction: row-reverse;
	}

	.message-movie-sidebar
	{
		flex: 1;
		width: @xf-snog_moviesMessageSidebarWidth;
		margin: 0 15px 0 15px !important;
		float: left;
		text-align: center;
		vertical-align:top;

		.movie-poster
		{
			padding-bottom: 10px;
			img {
				max-width: @xf-snog_moviesMessagePosterMaxWidth;
			}
		}
	}
	
	.message-movie-main
	{
		width: 100%;
		
		.message--movie
		{
			padding: 10px !important;
		}
	}

	.movie-company img
	{
		height: @xf-snog_movies_productionCompanyImgHeight;
		width: @xf-snog_movies_productionCompanyImgWidth;
	}
}

.message-movieTabs
{
	padding-top: 10px;
}

.movieinfo {
	display:none;
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
	&.structItem-cell--movie
	{
		text-align: center;
		width: 92px;
		position: relative;
	}
	
	.structItem-secondaryIcon
	{
		position: absolute;
		right: +10px !important;
		bottom: -5px;
	}
}

.moviePoster {
	margin-top:5px;
	width: @xf-snog_movies_posterImageWidth;
	height: @xf-snog_movies_posterImageHeight;
	position: relative;
}

.message-movieUserInfo
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