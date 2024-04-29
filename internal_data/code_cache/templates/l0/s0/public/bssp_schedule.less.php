<?php
// FROM HASH: 1053b1506901b3b43bda438a5ca96c8e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.schedule-input
{
	color: @xf-linkColor;
	position: relative;
	cursor: pointer;
	display: inline-flex;
	
	&.ml {
		margin-left: 5px;
	}
	
	@media (max-width: @xf-responsiveNarrow) {
		.bootstrap-datetimepicker-widget.dropdown-menu {
			width: 17em;
		}
	}
}

.schedule-reset
{
	font-size: 10px;
	width: 18px;
	height: 20px;
	text-align: center;
	padding-top: 4px;

	.schedule-input.scheduled &
	{
		font-size: 12px;
		padding-top: 2px;
		
		.icon
		{
			&:before
			{
				.m-faContent(@fa-var-times);
			}
		}
	}

	.icon
	{
		&:before
		{
			.m-faBase();
			.m-faContent(@fa-var-chevron-down);
		}
	}
}

.js-scheduleCheckbox
{
	display: none;
}

.js-scheduleDate
{
	position: absolute;
	top: 0;
	left: 0;
	visibility: hidden;
	opacity: 0;
}';
	return $__finalCompiled;
}
);