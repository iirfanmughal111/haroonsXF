<?php
// FROM HASH: 4fe449f84172a8c18787496d308a4d93
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Article');
	$__finalCompiled .= '
	
';
	$__templater->includeJs(array(
		'src' => 'botGPT/submit.js',
	));
	$__finalCompiled .= '

	<div class="block-container">
		<div class="block-body">
			
			' . $__templater->formTextAreaRow(array(
		'name' => 'article',
		'rows' => '7',
		'autosize' => 'true',
	), array(
		'label' => 'Article',
		'hint' => 'Required',
	)) . '
		
		';
	if ($__vars['botOptions']) {
		$__finalCompiled .= '	
			';
		$__compilerTemp1 = array();
		if ($__templater->isTraversable($__vars['botOptions'])) {
			foreach ($__vars['botOptions'] AS $__vars['botOption']) {
				$__compilerTemp1[] = array(
					'value' => $__vars['botOption']['bot_instruction'],
					'label' => $__templater->escape($__vars['botOption']['title']),
					'_type' => 'option',
				);
			}
		}
		$__finalCompiled .= $__templater->formSelectRow(array(
			'name' => 'option_bot',
		), $__compilerTemp1, array(
			'label' => 'Bot instructions',
		)) . '
		';
	}
	$__finalCompiled .= '
		</div>
	     ' . $__templater->formSubmitRow(array(
		'icon' => 'send',
		'data-xf-click' => 'insert-article',
		'sticky' => 'true',
	), array(
		'label' => 'send',
	)) . '
		' . '
	</div>';
	return $__finalCompiled;
}
);