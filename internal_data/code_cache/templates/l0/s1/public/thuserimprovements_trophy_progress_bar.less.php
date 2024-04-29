<?php
// FROM HASH: 39639af693d2ba81d170aa3be11d6031
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.th-trophyProgressContainer {
	overflow: auto;
}

.th-trophyProgressBarContainer {
	list-style: none;
	display: flex;
	padding: 0;
	margin: 0;
}

.th-trophyProgress {
	display: block;
	flex: 1;
	.contentRow-figure {
		display: block;
		margin: 0 auto;
		padding: 5px;
	}
	.th-trophyProgress-trophyTitle {
		display: block;
		text-align: center;
		font-size: @xf-fontSizeNormal;
		padding: 20px 5px 10px;
		&.th-trophyProgress-trophyTitle--selected {
			font-weight: 700;
		}
	}
	&:first-child .th-trophyProgressBar .th-trophyProgressBar-bar::before {
		content: \'0\';
		position: absolute;
		left: 0;
		bottom: -20px;
		width: 40px;
		text-align: left;
		font-size: @xf-fontSizeSmaller;
	}
	&:first-child .th-trophyProgressBar, &:first-child .th-trophyProgressBar .th-trophyProgressBar-bar {
		border-top-left-radius: 20px;
		border-bottom-left-radius: 20px;
	}
	&:last-child .th-trophyProgressBar {
		border-top-right-radius: 20px;
		border-bottom-right-radius: 20px;
		.th-trophyProgressBar-bar.th-trophyProgressBar-bar--full {
			border-top-right-radius: 20px;
			border-bottom-right-radius: 20px;
		}
		.th-trophyProgressBar-max {
			right: 0;
			text-align: right;
		}
	}
	&:not(:last-child) .th-trophyProgressBar {
		border-right: 0;
	}
}

.th-trophyProgressBar {
	display: block;
	position: relative;
    background: @xf-thuserimprovements_trophyProgressBarBackground;
	border: 1px solid @xf-thuserimprovements_trophyProgressBarBorder;
	.th-trophyProgressBar-bar {
		min-width: 10px;
		height: 20px;
		background-color: @xf-thuserimprovements_trophyProgressBarForeground;
		&:not(.th-trophyProgressBar-bar--full) {
			border-top-right-radius: 20px;
			border-bottom-right-radius: 20px;
		}
		&.th-trophyProgressBar-bar--empty {
			width: 0;
			min-width: 0;
		}
		&.th-trophyProgressBar-bar--full {
			width: 100%;
		}
	}
	.th-trophyProgressBar-max {
		position: absolute;
		right: -20px;
		bottom: -20px;
		width: 40px;
		text-align: center;
		font-size: @xf-fontSizeSmaller;
	}
}';
	return $__finalCompiled;
}
);