<?php
// FROM HASH: 31c4bf0f46facd97a156f4fe111438d8
return array(
'macros' => array('select_groups' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'userGroupIds' => '!',
		'userGroups' => '!',
		'withRow' => '1',
		'selectName' => 'user_group_ids',
		'required' => 'true',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['userGroups'])) {
		foreach ($__vars['userGroups'] AS $__vars['group']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['group']['user_group_id'],
				'label' => $__templater->escape($__vars['group']['title']),
				'_type' => 'option',
			);
		}
	}
	$__vars['inner'] = $__templater->preEscaped('
		<ul class="inputList">
			<li>' . $__templater->formSelect(array(
		'name' => $__vars['selectName'] . '[]',
		'value' => $__vars['userGroupIds'],
		'size' => '7',
		'multiple' => 'multiple',
		'required' => $__vars['required'],
		'id' => 'js-applicableUserGroups',
	), $__compilerTemp1) . '</li>

			' . $__templater->formCheckBox(array(
	), array(array(
		'label' => 'Select all',
		'id' => 'js-selectAllUsergroups',
		'_type' => 'option',
	))) . '
		</ul>
	');
	$__finalCompiled .= '

	';
	if ($__vars['withRow']) {
		$__finalCompiled .= '
		' . $__templater->formRow('
			' . $__templater->filter($__vars['inner'], array(array('raw', array()),), true) . '
		', array(
			'rowtype' => 'input',
			'label' => 'User Groups to hide media tags
',
			'explain' => 'Select UserGroups to hide media tags. The Users of  these Usergroups can\'t see the content of media tags that are selected to hide above (you can select multiple)',
		)) . '
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['inner'], array(array('raw', array()),), true) . '
	';
	}
	$__finalCompiled .= '

	';
	$__templater->inlineJs('
		$(function()
		{
			$(\'#js-selectAllUsergroups\').click(function(e)
			{
				$(\'#js-applicableUserGroups\').find(\'option:enabled:not([value=""])\').prop(\'selected\', this.checked);
			});
		});
	');
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'select_media_tags' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaIds' => '!',
		'mediaSites' => '!',
		'withRow' => '1',
		'selectName' => 'media_site_ids',
		'required' => 'true',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['mediaSites'])) {
		foreach ($__vars['mediaSites'] AS $__vars['mediaSite']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['mediaSite']['media_site_id'],
				'label' => $__templater->escape($__vars['mediaSite']['site_title']),
				'_type' => 'option',
			);
		}
	}
	$__vars['inner'] = $__templater->preEscaped('
		<ul class="inputList">
			<li>' . $__templater->formSelect(array(
		'name' => $__vars['selectName'] . '[]',
		'value' => $__vars['mediaIds'],
		'size' => '7',
		'multiple' => 'multiple',
		'required' => $__vars['required'],
		'id' => 'js-applicableMediaSites',
	), $__compilerTemp1) . '</li>

			' . $__templater->formCheckBox(array(
	), array(array(
		'label' => 'Select all',
		'id' => 'js-selectAllMediaSites',
		'_type' => 'option',
	))) . '
		</ul>
	');
	$__finalCompiled .= '

	';
	if ($__vars['withRow']) {
		$__finalCompiled .= '
		' . $__templater->formRow('
			' . $__templater->filter($__vars['inner'], array(array('raw', array()),), true) . '
		', array(
			'rowtype' => 'input',
			'label' => 'Media sites (media tags)',
			'explain' => 'Select media sites (tags) which you want to hide (you can select multiple)',
		)) . '
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['inner'], array(array('raw', array()),), true) . '
	';
	}
	$__finalCompiled .= '

	';
	$__templater->inlineJs('
		$(function()
		{
			$(\'#js-selectAllMediaSites\').click(function(e)
			{
				$(\'#js-applicableMediaSites\').find(\'option:enabled:not([value=""])\').prop(\'selected\', this.checked);
			});
		});
	');
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '


';
	return $__finalCompiled;
}
);