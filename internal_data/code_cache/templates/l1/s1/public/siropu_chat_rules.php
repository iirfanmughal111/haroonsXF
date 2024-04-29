<?php
// FROM HASH: 4d9b497f62794c00d6f583d1e36abe5b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Rules');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			<div class="block-row">
				';
	if ($__vars['xf']['options']['siropuChatRules']) {
		$__finalCompiled .= '
					' . $__templater->func('bb_code', array($__vars['xf']['options']['siropuChatRules'], 'siropu_chat', null, ), true) . '
				';
	} else {
		$__finalCompiled .= '
					' . 'There are currently no special chat rules.' . '
				';
	}
	$__finalCompiled .= '
			</div>
			';
	if ($__templater->method($__vars['xf']['visitor'], 'canEditSiropuChatRules', array())) {
		$__finalCompiled .= '
				<div class="block-row">
					' . $__templater->button('Edit rules', array(
			'href' => $__templater->func('link', array('chat/edit-rules', ), false),
			'class' => 'js-overlayClose',
			'icon' => 'write',
			'data-xf-click' => 'overlay',
		), '', array(
		)) . '
				</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);