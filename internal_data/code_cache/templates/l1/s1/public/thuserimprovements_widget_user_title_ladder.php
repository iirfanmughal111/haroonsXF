<?php
// FROM HASH: 872dcafa795d13cb7ceba71b5fd28734
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('thuserimprovements_widget_user_title_ladder.less');
	$__finalCompiled .= '

';
	if ($__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
	<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
		<div class="block-container">
			<h3 class="block-minorHeader">' . $__templater->escape($__vars['title']) . '</h3>
			<div class="block-body block-row">
				<div class="user-title-ladder-progress--container" title="' . $__templater->escape($__vars['tooltip']) . '" data-xf-init="tooltip">
					<div class="user-title-ladder-progress--bar" style="width: ' . $__templater->escape($__vars['progress']) . '%">
						&nbsp;';
		if ($__vars['progress'] >= 50) {
			$__finalCompiled .= $__templater->escape($__vars['progress']) . '%';
		}
		$__finalCompiled .= '
					</div>
					';
		if ($__vars['progress'] < 50) {
			$__finalCompiled .= $__templater->escape($__vars['progress']) . '%';
		}
		$__finalCompiled .= '
				</div>
			</div>
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);