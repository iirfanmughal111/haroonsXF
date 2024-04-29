<?php
// FROM HASH: d96e66873427f04ec9100a465a2151fe
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['trophyCategory']['title']));
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped('Trophies'), $__templater->func('link', array('trophies', ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->includeCss('thuserimprovements_trophy_view.less');
	$__finalCompiled .= '

' . $__templater->callMacro('thuserimprovements_trophy_progress_macros', 'trophy_progress', array(
		'trophies' => $__vars['trophies'],
		'trophyProgressCriteria' => $__vars['trophyProgressCriteria'],
		'progressValue' => $__vars['progressValue'],
	), $__vars) . '

<div class="block">
	<div class="block-container">
		<h2 class="block-tabHeader block-tabHeader--trophyTabs tabs hScroller" data-xf-init="tabs h-scroller" role="tablist"
			data-panes=".js-trophyCategoryTabPanes" data-state="replace">
			<span class="hScroller-scroll">
				' . '
				<a class="tabs-tab is-active"
					role="tab" tabindex="0" aria-controls="trophies">' . 'Trophies' . '</a>
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

<ul class="block tabPanes js-trophyCategoryTabPanes">
	' . '
	<li class="is-active" role="tabpanel" id="trophies">
		';
	if (!$__templater->test($__vars['trophiesPrepared'], 'empty', array())) {
		$__finalCompiled .= '
			<div class="block-container">
				<ol class="block-body">
					';
		if ($__templater->isTraversable($__vars['trophiesPrepared'])) {
			foreach ($__vars['trophiesPrepared'] AS $__vars['trophy']) {
				$__finalCompiled .= '
						' . $__templater->callMacro('thuserimprovements_trophy_macros', 'trophy_item', array(
					'trophy' => $__vars['trophy'],
					'showRecentlyAwardedUsers' => true,
				), $__vars) . '
					';
			}
		}
		$__finalCompiled .= '
				</ol>
				<div class="block-footer">
					';
		$__vars['trophiesEarned'] = '0';
		$__finalCompiled .= '
					';
		if ($__templater->isTraversable($__vars['trophiesPrepared'])) {
			foreach ($__vars['trophiesPrepared'] AS $__vars['trophy']) {
				$__finalCompiled .= '
						';
				if ($__vars['trophy']['earned']) {
					$__finalCompiled .= '
							';
					$__vars['trophiesEarned'] = ($__vars['trophiesEarned'] + 1);
					$__finalCompiled .= '
						';
				}
				$__finalCompiled .= '
					';
			}
		}
		$__finalCompiled .= '
					' . 'You\'ve earned ' . $__templater->escape($__vars['trophiesEarned']) . ' out of ' . $__templater->func('count', array($__vars['trophiesPrepared'], ), true) . ' trophies in this category.' . '
				</div>
			</div>
		';
	} else {
		$__finalCompiled .= '
			<div class="blockMessage">' . 'There are no trophies in this category.' . '</div>
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
	$__templater->modifySidebarHtml('_xfWidgetPositionSidebar53418115e6cc999f2175f4e5d2522177', $__templater->widgetPosition('thuserimprovements_trophy_category_view_sidebar', array()), 'replace');
	return $__finalCompiled;
}
);