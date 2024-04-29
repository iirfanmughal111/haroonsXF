<?php
// FROM HASH: 417d8a0ff0532337f68c27901a02f65b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['trophy']['trophy_points'] === 1) {
		$__finalCompiled .= '
	';
		$__vars['trophyPoints'] = $__templater->preEscaped('(1 point)');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__vars['trophyPoints'] = $__templater->preEscaped('(' . $__templater->filter($__vars['trophy']['trophy_points'], array(array('number_short', array()),), true) . ' points)');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['trophy']['title']) . ' ' . $__templater->escape($__vars['trophyPoints']));
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '
';
	$__templater->pageParams['pageDescription'] = $__templater->preEscaped($__templater->escape($__vars['trophy']['description']));
	$__templater->pageParams['pageDescriptionMeta'] = true;
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped('Trophies'), $__templater->func('link', array('trophies', ), false), array(
	));
	$__finalCompiled .= '
';
	if (!$__templater->test($__vars['trophy']['THUITrophyCategory'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['trophy']['THUITrophyCategory']['title'])), $__templater->func('link', array('trophy-categories', $__vars['trophy']['THUITrophyCategory'], ), false), array(
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->includeCss('thuserimprovements_trophy_view.less');
	$__finalCompiled .= '

' . $__templater->callMacro('thuserimprovements_trophy_progress_macros', 'trophy_progress', array(
		'trophies' => $__vars['trophies'],
		'trophyProgressCriteria' => $__vars['trophyProgressCriteria'],
		'progressValue' => $__vars['progressValue'],
		'selectedTrophy' => $__vars['trophy'],
	), $__vars) . '

<div class="block">
	<div class="block-container">
		<h2 class="block-tabHeader block-tabHeader--trophyTabs tabs hScroller" data-xf-init="tabs h-scroller" role="tablist"
			data-panes=".js-trophyTabPanes" data-state="replace">
			<span class="hScroller-scroll">
				' . '
				<a class="tabs-tab is-active"
					role="tab" tabindex="0" aria-controls="awarded">' . 'Awarded' . ' (' . $__templater->escape($__vars['total']) . ')</a>
				' . '
				';
	if ($__vars['trophyProgressCriteria']['valueKey']) {
		$__finalCompiled .= '
					<a class="tabs-tab"
						role="tab" tabindex="1" aria-controls="' . $__templater->escape($__vars['trophyProgressCriteria']['valueKey']) . '">' . $__templater->escape($__vars['trophyProgressCriteria']['statsPhrase']) . '</a>
				';
	}
	$__finalCompiled .= '
				' . '
			</span>
		</h2>
	</div>
</div>

<ul class="block tabPanes js-trophyTabPanes">
	' . '
	<li class="is-active" role="tabpanel" id="awarded">
		';
	if (!$__templater->test($__vars['userTrophies'], 'empty', array())) {
		$__finalCompiled .= '
			<div class="block-container">
				<ol class="block-body">
					';
		if ($__templater->isTraversable($__vars['userTrophies'])) {
			foreach ($__vars['userTrophies'] AS $__vars['userTrophy']) {
				$__finalCompiled .= '
						<li class="block-row block-row--separated">
							';
				if ($__vars['trophyProgressCriteria']['valueSubKey']) {
					$__finalCompiled .= '
								' . $__templater->callMacro('member_list_macros', 'item', array(
						'user' => $__vars['userTrophy']['User'],
						'extraData' => $__vars['userTrophy']['User'][$__vars['trophyProgressCriteria']['valueKey']][$__vars['trophyProgressCriteria']['valueSubKey']],
						'extraDataBig' => true,
					), $__vars) . '
							';
				} else if ($__vars['trophyProgressCriteria']['valueKey']) {
					$__finalCompiled .= '
								' . $__templater->callMacro('member_list_macros', 'item', array(
						'user' => $__vars['userTrophy']['User'],
						'extraData' => $__vars['userTrophy']['User'][$__vars['trophyProgressCriteria']['valueKey']],
						'extraDataBig' => true,
					), $__vars) . '
							';
				} else {
					$__finalCompiled .= '
								' . $__templater->callMacro('member_list_macros', 'item', array(
						'user' => $__vars['userTrophy']['User'],
					), $__vars) . '
							';
				}
				$__finalCompiled .= '
						</li>
					';
			}
		}
		$__finalCompiled .= '
				</ol>
			</div>
			' . $__templater->func('page_nav', array(array(
			'link' => 'trophies',
			'data' => $__vars['trophy'],
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'wrapperclass' => 'block-outer block-outer--after',
			'perPage' => $__vars['perPage'],
		))) . '
		';
	} else {
		$__finalCompiled .= '
			<div class="blockMessage">' . 'No users have earned this trophy.' . '</div>
		';
	}
	$__finalCompiled .= '
	</li>
	' . '
	';
	if ($__vars['trophyProgressCriteria']['valueKey']) {
		$__finalCompiled .= '
		<li data-href="' . $__templater->func('link', array('trophies/stats/' . $__vars['trophyProgressCriteria']['valueKey'], '', $__vars['trophyProgressCriteria']['additionalData'], ), true) . '" role="tabpanel" aria-labelledby="' . $__templater->escape($__vars['trophyProgressCriteria']['valueKey']) . '">
			<div class="blockMessage">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
		</li>
	';
	}
	$__finalCompiled .= '
	' . '
</ul>

';
	$__templater->modifySidebarHtml('_xfWidgetPositionSidebar0d6ef71b623a4d05e0bf52ceeb741ee5', $__templater->widgetPosition('thuserimprovements_trophy_view_sidebar', array()), 'replace');
	return $__finalCompiled;
}
);