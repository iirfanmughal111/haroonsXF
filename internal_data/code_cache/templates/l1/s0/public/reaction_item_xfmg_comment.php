<?php
// FROM HASH: 0537b238677d982c3368d59760b4be24
return array(
'macros' => array('reaction_snippet' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'reactionUser' => '!',
		'reactionId' => '!',
		'comment' => '!',
		'date' => '!',
		'fallbackName' => 'Unknown member',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="contentRow-title">
		';
	if ($__vars['comment']['user_id'] == $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
			';
		if ($__vars['comment']['content_type'] == 'xfmg_media') {
			$__finalCompiled .= '
				' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to your comment on media item ' . (((('<a href="' . $__templater->func('link', array('media/comments', $__vars['comment'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['comment']['Content']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
			';
		} else {
			$__finalCompiled .= '
				' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to your comment on album ' . (((('<a href="' . $__templater->func('link', array('media/comments', $__vars['comment'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['comment']['Content']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
			';
		}
		$__finalCompiled .= '
		';
	} else {
		$__finalCompiled .= '
			';
		if ($__vars['comment']['content_type'] == 'xfmg_media') {
			$__finalCompiled .= '
				' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to ' . $__templater->escape($__vars['comment']['username']) . '\'s comment on media item ' . (((('<a href="' . $__templater->func('link', array('media/comments', $__vars['comment'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['comment']['Content']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
			';
		} else {
			$__finalCompiled .= '
				' . '' . $__templater->func('username_link', array($__vars['reactionUser'], false, array('defaultname' => $__vars['fallbackName'], ), ), true) . ' reacted to ' . $__templater->escape($__vars['comment']['username']) . '\'s comment on album ' . (((('<a href="' . $__templater->func('link', array('media/comments', $__vars['comment'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['comment']['Content']['title'])) . '</a>') . ' with ' . $__templater->filter($__templater->func('alert_reaction', array($__vars['reactionId'], 'medium', ), false), array(array('preescaped', array()),), true) . '.' . '
			';
		}
		$__finalCompiled .= '
		';
	}
	$__finalCompiled .= '
	</div>

	<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['comment']['message'], $__vars['xf']['options']['newsFeedMessageSnippetLength'], ), true) . '</div>

	<div class="contentRow-minor">' . $__templater->func('date_dynamic', array($__vars['date'], array(
	))) . '</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . $__templater->callMacro(null, 'reaction_snippet', array(
		'reactionUser' => $__vars['reaction']['ReactionUser'],
		'reactionId' => $__vars['reaction']['reaction_id'],
		'comment' => $__vars['content'],
		'date' => $__vars['reaction']['reaction_date'],
	), $__vars);
	return $__finalCompiled;
}
);