<?php
// FROM HASH: 0fab495c02b595dfea47214737e0125c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Watched media');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['mediaItems'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['mediaItems'])) {
			foreach ($__vars['mediaItems'] AS $__vars['mediaItem']) {
				$__compilerTemp1 .= '
						';
				$__vars['mediaWatch'] = $__vars['mediaItem']['Watch'][$__vars['xf']['visitor']['user_id']];
				$__compilerTemp1 .= '
						';
				$__compilerTemp2 = '';
				if ($__vars['mediaWatch']['notify_on'] == 'comment') {
					$__compilerTemp2 .= '
									<li>' . 'New comment' . '</li>
								';
				}
				$__compilerTemp3 = '';
				if ($__vars['mediaWatch']['send_email']) {
					$__compilerTemp3 .= '<li>' . 'Emails' . '</li>';
				}
				$__compilerTemp4 = '';
				if ($__vars['mediaWatch']['send_alert']) {
					$__compilerTemp4 .= '<li>' . 'Alerts' . '</li>';
				}
				$__vars['extra'] = $__templater->preEscaped('
							<ul class="listInline listInline--bullet">
								' . $__compilerTemp2 . '
								' . $__compilerTemp3 . '
								' . $__compilerTemp4 . '
							</ul>
						');
				$__compilerTemp1 .= '
						' . $__templater->callMacro('xfmg_media_list_macros', 'media_list_item_struct_item', array(
					'item' => $__vars['mediaItem'],
					'chooseName' => 'ids',
					'extraInfo' => $__vars['extra'],
				), $__vars) . '
					';
			}
		}
		$__finalCompiled .= $__templater->form('
		<div class="block-outer">
			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'watched/media',
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '

			<div class="block-outer-opposite">
				' . $__templater->button('Manage watched media', array(
			'class' => 'button--link menuTrigger',
			'data-xf-click' => 'menu',
			'aria-expanded' => 'false',
			'aria-haspopup' => 'true',
		), '', array(
		)) . '
				<div class="menu" data-menu="menu" aria-hidden="true">
					<div class="menu-content">
						<h3 class="menu-header">' . 'Manage watched media' . '</h3>
						' . '
						<a href="' . $__templater->func('link', array('watched/media/manage', null, array('state' => 'send_email:off', ), ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Disable email notification' . '</a>
						<a href="' . $__templater->func('link', array('watched/media/manage', null, array('state' => 'send_alert:off', ), ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Disable alerts' . '</a>
						<a href="' . $__templater->func('link', array('watched/media/manage', null, array('state' => 'delete', ), ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Stop watching media' . '</a>
						' . '
					</div>
				</div>
			</div>
		</div>

		<div class="block-container">
			<div class="block-body">
				<div class="structItemContainer">
					' . $__compilerTemp1 . '
				</div>
			</div>
			<div class="block-footer block-footer--split">
				<span class="block-footer-counter"></span>
				<span class="block-footer-select">' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'check-all' => '< .block-container',
			'label' => 'Select all',
			'_type' => 'option',
		))) . '</span>
				<span class="block-footer-controls">
					' . $__templater->formSelect(array(
			'name' => 'watch_action',
			'class' => 'input--inline',
		), array(array(
			'label' => 'With selected' . $__vars['xf']['language']['ellipsis'],
			'_type' => 'option',
		),
		array(
			'value' => 'send_email:on',
			'label' => 'Enable email notification',
			'_type' => 'option',
		),
		array(
			'value' => 'send_email:off',
			'label' => 'Disable email notification',
			'_type' => 'option',
		),
		array(
			'value' => 'send_alert:on',
			'label' => 'Enable alerts',
			'_type' => 'option',
		),
		array(
			'value' => 'send_alert:off',
			'label' => 'Disable alerts',
			'_type' => 'option',
		),
		array(
			'value' => 'delete',
			'label' => 'Stop watching',
			'_type' => 'option',
		))) . '
					' . $__templater->button('Go', array(
			'type' => 'submit',
		), '', array(
		)) . '
				</span>
			</div>
		</div>

		<div class="block-outer block-outer--after">
			' . $__templater->func('page_nav', array(array(
			'link' => 'watched/media',
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'perPage' => $__vars['perPage'],
		))) . '
		</div>
	', array(
			'action' => $__templater->func('link', array('watched/media/update', ), false),
			'ajax' => 'true',
			'class' => 'block',
			'autocomplete' => 'off',
		)) . '
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">
		';
		if ($__vars['page'] > 1) {
			$__finalCompiled .= '
			' . 'There are no media items to display.' . '
		';
		} else {
			$__finalCompiled .= '
			' . 'You are not watching any media items.' . '
		';
		}
		$__finalCompiled .= '
	</div>
';
	}
	return $__finalCompiled;
}
);