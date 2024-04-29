<?php
// FROM HASH: e77c154b8143e30b12a8d802eb60b83a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Start private chat conversation with ' . $__templater->escape($__vars['user']['username']) . '');
	$__finalCompiled .= '

';
	$__templater->inlineJs('
	$(function()
	{
		$(\'#startChat\').on(\'submit\', function(e)
		{
			var input = $(this).find(\'textarea[name="message"]\');

			if (!input.val().trim())
			{
				input.focus();
				e.preventDefault();
			}
		});
	});
');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextAreaRow(array(
		'name' => 'message',
		'rows' => '5',
	), array(
		'label' => 'Message',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Start private conversation',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('chat/conversation/start', $__vars['user'], array('redirect' => true, ), ), false),
		'id' => 'startChat',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);