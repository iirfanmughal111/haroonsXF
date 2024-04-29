<?php
// FROM HASH: 9c16d7edefda3997a55dea5a40f7c421
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '{user} reacted to your album {item} with ' . $__templater->func('reaction_title', array($__vars['extra']['reaction_id'], ), true) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:media/albums', $__vars['content'], ), true) . '</push:url>
<push:tag>xfmg_album_reaction_' . $__templater->escape($__vars['content']['album_id']) . '_' . $__templater->escape($__vars['extra']['reaction_id']) . '</push:tag>';
	return $__finalCompiled;
}
);