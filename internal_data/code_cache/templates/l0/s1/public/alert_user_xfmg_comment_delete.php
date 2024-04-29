<?php
// FROM HASH: 88f50a5809c43fc881075da9d7f5d6d7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['extra']['content_type'] == 'xfmg_album') {
		$__finalCompiled .= '
	' . 'Your comment on the album ' . (((('<a href="' . $__templater->escape($__vars['extra']['link'])) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['extra']['title'])) . '</a>') . ' was deleted.' . '
';
	} else {
		$__finalCompiled .= '
	' . 'Your comment on the media item ' . (((('<a href="' . $__templater->escape($__vars['extra']['link'])) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['extra']['title'])) . '</a>') . ' was deleted.' . '
';
	}
	$__finalCompiled .= '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);