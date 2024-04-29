<?php
// FROM HASH: f92f80c87415b90e7e24c7c8236b418e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'a[data-nav-id="siropuChat"]
{
	.badge--highlighted
	{
		background: @xf-siropuChatNoUserCountBadgeColor;
		color: @xf-siropuChatNoUserCountColor;
	}
	.badge--active
	{
		background: @xf-siropuChatActiveUserCountBadgeColor;
		color: @xf-siropuChatActiveUserCountColor;
	}
}
a.p-navgroup-link--chat.badgeContainer.badgeContainer--highlighted:after
{
	background: @xf-siropuChatActiveUserCountBadgeColor;
	color: @xf-siropuChatActiveUserCountColor;
}
button[id="xfCustom_chat-1"]
{
	display: none;
}
.p-navgroup-link--chat
{
	display: none;
}
@media (max-width: @xf-responsiveNarrow)
{
	.p-navgroup-link--chat
	{
		display: initial;
	}
}';
	return $__finalCompiled;
}
);