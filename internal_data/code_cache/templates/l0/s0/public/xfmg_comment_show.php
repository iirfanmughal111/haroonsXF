<?php
// FROM HASH: 0babbd7862b0309ed4a7cf125730fac1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['canInlineMod']) {
		$__finalCompiled .= '
	';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
			'min' => '1',
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="xfmg_comment" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('xfmg_comment_macros', 'comment', array(
		'comment' => $__vars['comment'],
		'content' => $__vars['content'],
		'linkPrefix' => (('media/' . (($__vars['content']['content_type'] == 'xfmg_media') ? 'media' : 'album')) . '-comments'),
	), $__vars) . '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);