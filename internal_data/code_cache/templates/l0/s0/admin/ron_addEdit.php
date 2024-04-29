<?php
// FROM HASH: 3649c9a383493ee68d4f708ab7b1072d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['ron'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add Ron Video');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit Ron Video' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['ron']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['ron'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('by-rons/delete', $__vars['ron'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['ron']['title'],
		'maxlength' => $__templater->func('max_length', array($__vars['ron'], 'title', ), false),
		'autofocus' => 'true',
		'required' => 'true',
	), array(
		'label' => 'Title',
	)) . '
			
			' . $__templater->formTextBoxRow(array(
		'name' => 'video_url',
		'type' => 'url',
		'value' => $__vars['ron']['video_url'],
		'maxlength' => $__templater->func('max_length', array($__vars['ron'], 'video_url', ), false),
		'required' => 'true',
	), array(
		'label' => 'youtube_video_url',
		'explain' => 'Insert only YouTube Video URL',
	)) . '
			
		</div>
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('by-rons/save', $__vars['ron'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);