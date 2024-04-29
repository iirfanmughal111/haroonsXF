<?php
// FROM HASH: a6d045a634d7b7abfe868098fb69dd0a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['user'], 'canViewTHUIUsernameHistory', array()) AND $__templater->method($__vars['username_changes'], 'count', array())) {
		$__finalCompiled .= '
	<div class="buttonGroup-buttonWrapper kl-ui-username-change-button-wrapper">
		' . $__templater->button('', array(
			'class' => 'button--link menuTrigger',
			'data-xf-click' => 'menu',
			'aria-expanded' => 'false',
			'aria-haspopup' => 'true',
		), '', array(
		)) . '
		<div class="menu" data-menu="menu" aria-hidden="true">
			<div class="menu-content kl-username-change-menu-content">
				<h4 class="menu-header">' . 'Also known as' . '</h4>
				' . '
				';
		if ($__templater->isTraversable($__vars['username_changes'])) {
			foreach ($__vars['username_changes'] AS $__vars['record']) {
				$__finalCompiled .= '
					<dl class="menu-linkRow"><dt>' . $__templater->escape($__vars['record']['old_username']) . '</dt><dd><span class="record-until">' . 'until' . '</span> ';
				if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('klUI', 'klUIViewNameChangeTime', ))) {
					$__finalCompiled .= $__templater->func('date_dynamic', array($__vars['record']['change_date'], array(
					)));
				}
				$__finalCompiled .= '</dd></dl>
				';
			}
		}
		$__finalCompiled .= '
			</div>
		</div>
	</div>
	';
		$__templater->includeCss('thuserimprovements_username_change_history.less');
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);