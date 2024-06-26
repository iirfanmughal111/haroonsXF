<?php
// FROM HASH: d48d996a83686c8d0098d0860ea8c0ba
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '/*!
 * Datetimepicker for Bootstrap 3
 * version : 4.17.47
 * https://github.com/Eonasdan/bootstrap-datetimepicker/
 */
@bs-datetimepicker-timepicker-font-size: 1.2em;
@bs-datetimepicker-active-bg: @xf-textColorFeature;
@bs-datetimepicker-active-color: contrast(@xf-textColorFeature);;
@bs-datetimepicker-border-radius: @xf-blockBorderRadius;
@bs-datetimepicker-btn-hover-bg: @xf-contentHighlightBg;
@bs-datetimepicker-disabled-color: @xf-textColorMuted;
@bs-datetimepicker-alternate-color: @xf-textColorMuted;
@bs-datetimepicker-secondary-border-color: @xf-borderColorHeavy;
@bs-datetimepicker-secondary-border-color-rgba: rgba(0, 0, 0, 0.2);
@bs-datetimepicker-primary-border-color: @xf-contentBg;
@bs-datetimepicker-text-shadow: transparent;

// BOOTSTRAP VARIABLES
@screen-sm-min: @xf-responsiveNarrow;
@screen-md-min: @xf-responsiveMedium;
@screen-lg-min: @xf-responsiveWide;

.bootstrap-datetimepicker-widget {
    list-style: none;

    &.dropdown-menu {
		z-index: 9999;
        display: block;
        margin: 2px 0;
        padding: 4px;
        width: 19em;
		
		position: absolute;
		.m-dropShadow(0, 5px, 10px, 0, .25);
		
		background: @xf-contentBg;
		border: 1px solid;
		border-color: @xf-borderColorHeavy;
		border-radius: @xf-borderRadiusMedium;

        &.timepicker-sbs {
            @media (min-width: @screen-sm-min) {
                width: 38em;
            }

            @media (min-width: @screen-md-min) {
                width: 38em;
            }

            @media (min-width: @screen-lg-min) {
                width: 38em;
            }
        }

        &:before, &:after {
            content: \'\';
            display: inline-block;
            position: absolute;
        }

        &.bottom {
            &:before {
                border-left: 7px solid transparent;
                border-right: 7px solid transparent;
                border-bottom: 7px solid @bs-datetimepicker-secondary-border-color;
                border-bottom-color: @bs-datetimepicker-secondary-border-color-rgba;
                top: -7px;
                left: 7px;
            }

            &:after {
                border-left: 6px solid transparent;
                border-right: 6px solid transparent;
                border-bottom: 6px solid @bs-datetimepicker-primary-border-color;
                top: -6px;
                left: 8px;
            }
        }

        &.top {
            &:before {
                border-left: 7px solid transparent;
                border-right: 7px solid transparent;
                border-top: 7px solid @bs-datetimepicker-secondary-border-color;
                border-top-color: @bs-datetimepicker-secondary-border-color-rgba;
                bottom: -7px;
                left: 6px;
            }

            &:after {
                border-left: 6px solid transparent;
                border-right: 6px solid transparent;
                border-top: 6px solid @bs-datetimepicker-primary-border-color;
                bottom: -6px;
                left: 7px;
            }
        }

        &.pull-right {
            &:before {
                left: auto;
                right: 6px;
            }

            &:after {
                left: auto;
                right: 7px;
            }
        }
    }

    .list-unstyled {
		list-style: none;
		padding-left: 0;
        margin: 0;
    }

    a[data-action] {
        padding: 6px 0;
    }

    a[data-action]:active {
        box-shadow: none;
    }

    .timepicker-hour, .timepicker-minute, .timepicker-second {
        width: 54px;
        font-weight: bold;
        font-size: @bs-datetimepicker-timepicker-font-size;
        margin: 0;
    }

    button[data-action] {
        padding: 6px;
    }

    .btn[data-action="incrementHours"]::after {
        .sr-only();
        content: "Increment Hours";
    }

    .btn[data-action="incrementMinutes"]::after {
        .sr-only();
        content: "Increment Minutes";
    }

    .btn[data-action="decrementHours"]::after {
        .sr-only();
        content: "Decrement Hours";
    }

    .btn[data-action="decrementMinutes"]::after {
        .sr-only();
        content: "Decrement Minutes";
    }

    .btn[data-action="showHours"]::after {
        .sr-only();
        content: "Show Hours";
    }

    .btn[data-action="showMinutes"]::after {
        .sr-only();
        content: "Show Minutes";
    }

    .btn[data-action="togglePeriod"]::after {
        .sr-only();
        content: "Toggle AM/PM";
    }

    .btn[data-action="clear"]::after {
        .sr-only();
        content: "Clear the picker";
    }

    .btn[data-action="today"]::after {
        .sr-only();
        content: "Set the date to today";
    }

    .picker-switch {
        text-align: center;

        &::after {
            .sr-only();
            content: "Toggle Date and Time Screens";
        }

        td {
            padding: 0;
            margin: 0;
            height: auto;
            width: auto;
            line-height: inherit;

            span {
                line-height: 2.5;
                height: 2.5em;
                width: 100%;
            }
        }
    }

    table {
        width: 100%;
        margin: 0;


        & td,
        & th {
            text-align: center;
            border-radius: @bs-datetimepicker-border-radius;
        }

        & th {
            height: 20px;
            line-height: 20px;
            width: 20px;

            &.picker-switch {
                width: 145px;
            }

            &.disabled,
            &.disabled:hover {
                background: none;
                color: @bs-datetimepicker-disabled-color;
                cursor: not-allowed;
            }

            &.prev::after {
                .sr-only();
                content: "Previous Month";
            }

            &.next::after {
                .sr-only();
                content: "Next Month";
            }
        }

        & thead tr:first-child th {
            cursor: pointer;

            &:hover {
                background: @bs-datetimepicker-btn-hover-bg;
            }
        }

        & td {
            height: 54px;
            line-height: 54px;
            width: 54px;

            &.cw {
                font-size: .8em;
                height: 20px;
                line-height: 20px;
                color: @bs-datetimepicker-alternate-color;
            }

            &.day {
                height: 20px;
                line-height: 20px;
                width: 20px;
            }

            &.day:hover,
            &.hour:hover,
            &.minute:hover,
            &.second:hover {
                background: @bs-datetimepicker-btn-hover-bg;
                cursor: pointer;
            }

            &.old,
            &.new {
                color: @bs-datetimepicker-alternate-color;
            }

            &.today {
                position: relative;

                &:before {
                    content: \'\';
                    display: inline-block;
                    border: solid transparent;
                    border-width: 0 0 7px 7px;
                    border-bottom-color: @bs-datetimepicker-active-bg;
                    border-top-color: @bs-datetimepicker-secondary-border-color-rgba;
                    position: absolute;
                    bottom: 4px;
                    right: 4px;
                }
            }

            &.active,
            &.active:hover {
                background-color: @bs-datetimepicker-active-bg;
                color: @bs-datetimepicker-active-color;
                text-shadow: @bs-datetimepicker-text-shadow;
            }

            &.active.today:before {
                border-bottom-color: #fff;
            }

            &.disabled,
            &.disabled:hover {
                background: none;
                color: @bs-datetimepicker-disabled-color;
                cursor: not-allowed;
            }

            span {
                display: inline-block;
                width: 54px;
                height: 54px;
                line-height: 54px;
                margin: 2px 1.5px;
                cursor: pointer;
                border-radius: @bs-datetimepicker-border-radius;

                &:hover {
                    background: @bs-datetimepicker-btn-hover-bg;
                }

                &.active {
                    background-color: @bs-datetimepicker-active-bg;
                    color: @bs-datetimepicker-active-color;
                    text-shadow: @bs-datetimepicker-text-shadow;
                }

                &.old {
                    color: @bs-datetimepicker-alternate-color;
                }

                &.disabled,
                &.disabled:hover {
                    background: none;
                    color: @bs-datetimepicker-disabled-color;
                    cursor: not-allowed;
                }
            }
        }
    }

    &.usetwentyfour {
        td.hour {
            height: 27px;
            line-height: 27px;
        }
    }
	
	&.wider {
		width: 21em;
	}

	& .datepicker-decades .decade {
        line-height: 1.8em !important;
    }
}

.input-group.date {
    & .input-group-addon {
        cursor: pointer;
    }
}

.collapse {
    display: none;
    visibility: hidden;
	
	&.in {
		display: block;
		visibility: visible;
	}
}

.collapsing {
	position: relative;
	height: 0;
	overflow: hidden;
	transition: height .35s ease;
}


.glyphicon {
	&:before {
		.m-faBase();
	}
	
	&.glyphicon-time { .m-faBefore(@fa-var-clock); }
	&.glyphicon-calendar { .m-faBefore(@fa-var-calendar); }
	&.glyphicon-chevron-up { .m-faBefore(@fa-var-chevron-up); }
	&.glyphicon-chevron-down { .m-faBefore(@fa-var-chevron-down); }
}';
	return $__finalCompiled;
}
);