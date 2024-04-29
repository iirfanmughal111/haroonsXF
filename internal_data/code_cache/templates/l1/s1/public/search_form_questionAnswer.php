<?php
// FROM HASH: d7e41d69dc33b15f21d4524d6428d265
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Search Question&Answers');
	$__finalCompiled .= '

' . $__templater->callMacro('search_form_macros', 'keywords', array(
		'input' => $__vars['input'],
	), $__vars) . '
' . $__templater->callMacro('search_form_macros', 'user', array(
		'input' => $__vars['input'],
	), $__vars) . '
' . $__templater->callMacro('search_form_macros', 'date', array(
		'input' => $__vars['input'],
	), $__vars) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'c[min_reply_count]',
		'value' => $__templater->filter($__vars['input']['c']['min_reply_count'], array(array('default', array(0, )),), false),
		'min' => '0',
	), array(
		'label' => 'Minimum number of replies',
	)) . '

';
	if (!$__templater->test($__vars['prefixesGrouped'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = array(array(
			'value' => '',
			'label' => $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'],
			'_type' => 'option',
		));
		if ($__templater->isTraversable($__vars['prefixGroups'])) {
			foreach ($__vars['prefixGroups'] AS $__vars['groupId'] => $__vars['prefixGroup']) {
				if (($__templater->func('count', array($__vars['prefixesGrouped'][$__vars['groupId']], ), false) > 0)) {
					$__compilerTemp1[] = array(
						'label' => $__templater->func('prefix_group', array('thread', $__vars['groupId'], ), false),
						'_type' => 'optgroup',
						'options' => array(),
					);
					end($__compilerTemp1); $__compilerTemp2 = key($__compilerTemp1);
					if ($__templater->isTraversable($__vars['prefixesGrouped'][$__vars['groupId']])) {
						foreach ($__vars['prefixesGrouped'][$__vars['groupId']] AS $__vars['prefixId'] => $__vars['prefix']) {
							$__compilerTemp1[$__compilerTemp2]['options'][] = array(
								'value' => $__vars['prefixId'],
								'label' => $__templater->func('prefix_title', array('thread', $__vars['prefixId'], ), true),
								'_type' => 'option',
							);
						}
					}
				}
			}
		}
		$__finalCompiled .= $__templater->formSelectRow(array(
			'name' => 'c[prefixes][]',
			'size' => '7',
			'multiple' => 'true',
			'value' => $__templater->filter($__vars['input']['c']['prefixes'], array(array('default', array(array(0, ), )),), false),
		), $__compilerTemp1, array(
			'label' => 'Prefixes',
		)) . '
';
	}
	$__finalCompiled .= '

';
	if ($__vars['input']['c']['thread']) {
		$__finalCompiled .= '
	' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'c[thread]',
			'value' => $__vars['input']['c']['thread'],
			'selected' => true,
			'label' => 'restrict_search_to_specified_thread',
			'_type' => 'option',
		)), array(
		)) . '
';
	}
	$__finalCompiled .= '

';
	$__vars['forumsControlId'] = $__templater->func('unique_id', array(), false);
	$__finalCompiled .= '
';
	$__compilerTemp3 = array();
	if ($__templater->isTraversable($__vars['nodeTree'])) {
		foreach ($__vars['nodeTree'] AS $__vars['node']) {
			$__compilerTemp3[] = array(
				'value' => $__vars['node']['node_id'],
				'selected' => true,
				'label' => $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['node']['depth'], ), false), array(array('raw', array()),), true) . ' ' . $__templater->escape($__vars['node']['title']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formRow('

	<ul class="inputList">
		<li>' . $__templater->formSelect(array(
		'name' => 'c[nodes][]',
		'size' => '4',
		'value' => $__templater->filter($__vars['input']['c']['nodes'], array(array('default', array(array(0, ), )),), false),
		'id' => $__vars['forumsControlId'],
	), $__compilerTemp3) . '</li>
		
		' . '
	</ul>
', array(
		'rowtype' => 'input',
		'label' => 'Search in question forum
',
		'controlid' => $__vars['forumsControlId'],
	)) . '

' . $__templater->callMacro('search_form_macros', 'order', array(
		'isRelevanceSupported' => $__vars['isRelevanceSupported'],
		'options' => array('replies' => 'Most replies', ),
		'input' => $__vars['input'],
	), $__vars) . '

' . $__templater->callMacro('search_form_macros', 'grouped', array(
		'label' => 'Display results as threads',
		'input' => $__vars['input'],
	), $__vars);
	return $__finalCompiled;
}
);