<?php
// FROM HASH: 06a76c48b5c1781561e8b3d4037e18a6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' reacted to owner page <a ' . (('href="' . $__templater->func('link', array('bh_item/ownerpage/page', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink"') . '>' . ($__templater->func('prefix', array('thread', $__vars['content']['item_title'], ), true) . $__templater->escape($__vars['content']['Item']['item_title'])) . '</a> with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['extra']['reaction_id'], ), false), array(array('preescaped', array()),), true) . '';
	return $__finalCompiled;
}
);