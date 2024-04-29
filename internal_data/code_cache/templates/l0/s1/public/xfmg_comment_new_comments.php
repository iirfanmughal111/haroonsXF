<?php
// FROM HASH: 22f97d46aaf8fab9578ac206f1cd6f02
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['firstUnshownComment']) {
		$__finalCompiled .= '
	<div class="message">
		<div class="message-inner">
			<div class="message-cell message-cell--alert">
				' . 'There are more comments to display.' . ' <a href="' . $__templater->func('link', array('media/comments', $__vars['firstUnshownComment'], ), true) . '">' . 'View them?' . '</a>
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__templater->isTraversable($__vars['comments'])) {
		foreach ($__vars['comments'] AS $__vars['comment']) {
			$__finalCompiled .= '
	' . $__templater->callMacro('xfmg_comment_macros', 'comment', array(
				'comment' => $__vars['comment'],
				'content' => $__vars['content'],
				'linkPrefix' => $__vars['linkPrefix'],
			), $__vars) . '
';
		}
	}
	return $__finalCompiled;
}
);