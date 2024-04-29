<?php
// FROM HASH: d0e85c01bcbc6ddcc53f28c827b13238
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Room sanctions');
	$__finalCompiled .= '


<div class="block">
	<div class="block-container js-roomSanctionList' . $__templater->escape($__vars['room']['room_id']) . '">
		<ol class="block-body">
			';
	if ($__templater->func('count', array($__vars['sanctions'], ), false)) {
		$__finalCompiled .= '
				';
		if ($__templater->isTraversable($__vars['sanctions'])) {
			foreach ($__vars['sanctions'] AS $__vars['sanction']) {
				$__finalCompiled .= '
					<li class="block-row block-row--separated">
						';
				$__compilerTemp1 = '';
				if ($__vars['sanction']['sanction_end']) {
					$__compilerTemp1 .= '
									' . $__templater->func('date_dynamic', array($__vars['sanction']['sanction_end'], array(
					))) . '
								';
				} else {
					$__compilerTemp1 .= '
									' . 'Never' . '
								';
				}
				$__compilerTemp2 = '';
				if ($__vars['sanction']['sanction_reason']) {
					$__compilerTemp2 .= '
									<span title="' . $__templater->filter('Sanction reason', array(array('for_attr', array()),), true) . ': ' . $__templater->filter($__vars['sanction']['sanction_reason'], array(array('for_attr', array()),), true) . '" data-xf-init="tooltip">' . $__templater->fontAwesome('fa-question-circle', array(
					)) . '</span>
								';
				}
				$__compilerTemp3 = '';
				if ($__templater->method($__vars['sanction'], 'canRemove', array())) {
					$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('chat/sanction/edit', $__vars['sanction'], ), true) . '" title="' . $__templater->filter('Edit sanction', array(array('for_attr', array()),), true) . '" data-xf-init="tooltip" data-xf-click="overlay">' . $__templater->fontAwesome('fa-cog', array(
					)) . '</a>
									<a href="' . $__templater->func('link', array('chat/sanction/lift', $__vars['sanction'], ), true) . '" title="' . $__templater->filter('Lift sanction', array(array('for_attr', array()),), true) . '" data-xf-init="tooltip" data-xf-click="overlay">' . $__templater->fontAwesome('fa-times-circle', array(
					)) . '</a>
								';
				}
				$__vars['extraData'] = $__templater->preEscaped('
							<div style="text-align: right;">
								<b>' . 'Sanction type' . ':</b> ' . $__templater->escape($__templater->method($__vars['sanction'], 'getTypePhrase', array())) . '<br>
								<b>' . 'Sanction start' . ':</b> ' . $__templater->func('date_dynamic', array($__vars['sanction']['sanction_start'], array(
				))) . '<br>
								<b>' . 'Sanction end' . ':</b>
								' . $__compilerTemp1 . '
								<br><b>' . 'Sanction by' . ':</b> ' . $__templater->func('username_link', array($__vars['sanction']['Author'], false, array(
				))) . '
								' . $__compilerTemp2 . '
								' . $__compilerTemp3 . '
							</div>
						');
				$__finalCompiled .= '
						' . $__templater->callMacro('member_list_macros', 'item', array(
					'user' => $__vars['sanction']['User'],
					'extraData' => $__vars['extraData'],
				), $__vars) . '
					</li>
				';
			}
		}
		$__finalCompiled .= '
			';
	} else {
		$__finalCompiled .= '
				<li class="block-row">' . 'There are no user sanctions for this room.' . '</li>
			';
	}
	$__finalCompiled .= '
		</ol>
		';
	if ($__vars['hasMore']) {
		$__finalCompiled .= '
			<div class="block-footer">
				<span class="block-footer-controls">' . $__templater->button('
					' . 'More' . $__vars['xf']['language']['ellipsis'] . '
				', array(
			'href' => $__templater->func('link', array('chat/room/sanctions', $__vars['room'], array('page' => $__vars['page'] + 1, ), ), false),
			'data-xf-click' => 'inserter',
			'data-replace' => '.js-roomSanctionList' . $__vars['room']['room_id'],
			'data-scroll-target' => '< .overlay',
		), '', array(
		)) . '</span>
			</div>
		';
	}
	$__finalCompiled .= '
	</div>
</div>';
	return $__finalCompiled;
}
);