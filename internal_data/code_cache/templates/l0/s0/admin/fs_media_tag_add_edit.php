<?php
// FROM HASH: abba335f63264c4f40ce05d57fb56654
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['mediaTag'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add Media Tag');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit Media Tag' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['mediaTag']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['mediaTag'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('media-tag/delete', $__vars['mediaTag'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '


';
	$__vars['mediaTagSelection'] = $__templater->preEscaped('
	' . $__templater->callMacro('input_selection', 'select_media_tags', array(
		'mediaIds' => $__vars['mediaTag']['media_site_ids'],
		'mediaSites' => $__vars['mediaSites'],
	), $__vars) . '
');
	$__finalCompiled .= '

';
	$__vars['userGroupSelection'] = $__templater->preEscaped('
	' . $__templater->callMacro('input_selection', 'select_groups', array(
		'userGroupIds' => $__vars['mediaTag']['user_group_ids'],
		'userGroups' => $__vars['userGroups'],
	), $__vars) . '
');
	$__finalCompiled .= '




' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . '

			' . $__templater->filter($__vars['mediaTagSelection'], array(array('raw', array()),), true) . '

			' . $__templater->filter($__vars['userGroupSelection'], array(array('raw', array()),), true) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'custom_message',
		'value' => $__vars['mediaTag']['custom_message'],
		'required' => 'true',
	), array(
		'label' => 'Custom message',
	)) . '

		</div>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('media-tag/save', $__vars['mediaTag'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);