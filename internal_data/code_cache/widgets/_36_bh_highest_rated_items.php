<?php

return function($__templater, array $__vars, array $__options = [])
{
	$__widget = \XF::app()->widget()->widget('bh_highest_rated_items', $__options)->render();

	return $__widget;
};