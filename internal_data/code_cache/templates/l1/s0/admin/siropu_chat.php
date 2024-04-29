<?php
// FROM HASH: 21664d1b6f9c354e7dbfa03ac6db29d7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Chat');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageDescription'] = $__templater->preEscaped('Welcome to Chat by Siropu!');
	$__templater->pageParams['pageDescriptionMeta'] = true;
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<ul class="block-body block-row">
			<li><a href="' . $__templater->func('link', array('chat/commands', ), true) . '">' . 'Commands' . '</a></li>
			<li><a href="' . $__templater->func('link', array('chat/bot-responses', ), true) . '">' . 'Bot responses' . '</a></li>
			<li><a href="' . $__templater->func('link', array('chat/bot-messages', ), true) . '">' . 'Bot messages' . '</a></li>
		</ul>
	</div>
</div>';
	return $__finalCompiled;
}
);