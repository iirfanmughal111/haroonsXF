<?php
// FROM HASH: dac3611f9bd95e1649f9d48f783ed883
return array(
'macros' => array('option_form_block' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '',
		'options' => '!',
		'containerBeforeHtml' => '',
		'tabs' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (!$__templater->test($__vars['options'], 'empty', array())) {
		$__finalCompiled .= '
		<div class="block">
			';
		$__compilerTemp1 = '';
		$__vars['i'] = 0;
		if ($__templater->isTraversable($__vars['tabs'])) {
			foreach ($__vars['tabs'] AS $__vars['tabId'] => $__vars['tab']) {
				$__vars['i']++;
				$__compilerTemp1 .= '
							<a class="tabs-tab ' . ($__vars['tab']['active'] ? 'is-active' : '') . '" role="tab" tabindex="' . $__templater->escape($__vars['i']) . '"
							   id="' . $__templater->escape($__vars['tabId']) . '"
							   aria-controls="' . $__templater->escape($__vars['tabId']) . '"
							   href="' . $__templater->func('link', array('options/groups', $__vars['group'], ), true) . '#' . $__templater->escape($__vars['tabId']) . '">
								' . $__templater->escape($__vars['tab']['label']) . '
							</a>
						';
			}
		}
		$__compilerTemp2 = '';
		$__vars['i'] = 0;
		if ($__templater->isTraversable($__vars['tabs'])) {
			foreach ($__vars['tabs'] AS $__vars['tabId'] => $__vars['tab']) {
				$__vars['i']++;
				$__compilerTemp2 .= '
						<li class="' . ($__vars['tab']['active'] ? 'is-active' : '') . '" role="tabpanel" aria-labelledby="general-options">
							<div class="block-body">
								';
				if ($__templater->isTraversable($__vars['options'])) {
					foreach ($__vars['options'] AS $__vars['option']) {
						if ((($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] >= $__vars['tab']['start']) AND ($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] < $__vars['tab']['end']))) {
							$__compilerTemp2 .= '

									';
							if ($__vars['group']) {
								$__compilerTemp2 .= '
										';
								$__vars['curHundred'] = $__templater->func('floor', array($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] / 100, ), false);
								$__compilerTemp2 .= '
										';
								if (($__vars['curHundred'] > $__vars['hundred'])) {
									$__compilerTemp2 .= '
											';
									$__vars['hundred'] = $__vars['curHundred'];
									$__compilerTemp2 .= '
											<hr class="formRowSep"/>
										';
								}
								$__compilerTemp2 .= '
									';
							}
							$__compilerTemp2 .= '

									' . $__templater->callMacro('option_macros', 'option_row', array(
								'group' => $__vars['group'],
								'option' => $__vars['option'],
							), $__vars) . '
								';
						}
					}
				}
				$__compilerTemp2 .= '
							</div>
						</li>
					';
			}
		}
		$__finalCompiled .= $__templater->form('
				' . $__templater->filter($__vars['containerBeforeHtml'], array(array('raw', array()),), true) . '
				<h2 class="block-tabHeader tabs hScroller" data-xf-init="tabs h-scroller" data-state="replace" role="tablist">
					<span class="hScroller-scroll">
						' . '
						' . $__compilerTemp1 . '
						' . '
					</span>
				</h2>

				<ul class="tabPanes">
					' . $__compilerTemp2 . '
				</ul>

				' . $__templater->formSubmitRow(array(
			'sticky' => 'true',
			'icon' => 'save',
		), array(
		)) . '
			', array(
			'action' => $__templater->func('link', array('options/update', ), false),
			'ajax' => 'true',
			'class' => 'block-container',
		)) . '
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);