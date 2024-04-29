<?php
// FROM HASH: 27d9b6ab52b60e2512a8200798518411
return array(
'macros' => array('overview_row' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'node' => '!',
		'column' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="contentRow contentRow--alignMiddle">
		<div class="contentRow-figure">
			<span class="node-icon" aria-hidden="true">
				' . $__templater->fontAwesome('fa-browser', array(
	)) . '
			</span>
		</div>
		<div class="contentRow-main">
			
			<div class="contentRow-extra contentRow-extra--large">' . $__templater->escape($__vars['node'][$__vars['column']]) . ' ' . (($__vars['column'] != 'issue_count') ? '%' : '') . '</div>
			
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('forums', $__vars['node'], ), true) . '" data-xf-init="' . (($__vars['descriptionDisplay'] == 'tooltip') ? 'element-tooltip' : '') . '" data-shortcut="node-description">' . $__templater->escape($__vars['node']['title']) . '</a>
			</h3>
		</div>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('fs_wr_statsList.less');
	$__finalCompiled .= '


	<section class="block">		
		<div class="blockMessage fs-avrBlock">
			<h3 class="block-textHeader">
				' . 'Overall Solved Issues Average' . ': ' . $__templater->filter($__vars['overAllsolvedAverage'], array(array('number_short', array(2, )),), true) . ' %
			</h3>
		</div>
	</section>


	<section class="block">
		<div class="block-container">
			
			<div class="block-body">
				<ol class="memberOverviewBlocks">
					';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
									';
	if ($__templater->isTraversable($__vars['solvedPercentageNodes'])) {
		foreach ($__vars['solvedPercentageNodes'] AS $__vars['id'] => $__vars['node']) {
			$__compilerTemp1 .= '
										<li>
											' . $__templater->callMacro(null, 'overview_row', array(
				'node' => $__vars['node'],
				'column' => 'solved_percentage',
			), $__vars) . '
										</li>
									';
		}
	}
	$__compilerTemp1 .= '
								';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
									';
	if ($__templater->isTraversable($__vars['unsolvedPercentageNodes'])) {
		foreach ($__vars['unsolvedPercentageNodes'] AS $__vars['id'] => $__vars['node']) {
			$__compilerTemp2 .= '
										<li>
											' . $__templater->callMacro(null, 'overview_row', array(
				'node' => $__vars['node'],
				'column' => 'unsolved_percentage',
			), $__vars) . '
										</li>
									';
		}
	}
	$__compilerTemp2 .= '
								';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
									';
	if ($__templater->isTraversable($__vars['mostCompainsNodes'])) {
		foreach ($__vars['mostCompainsNodes'] AS $__vars['id'] => $__vars['node']) {
			$__compilerTemp3 .= '
										<li>
											' . $__templater->callMacro(null, 'overview_row', array(
				'node' => $__vars['node'],
				'column' => 'issue_count',
			), $__vars) . '
										</li>
									';
		}
	}
	$__compilerTemp3 .= '
								';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__finalCompiled .= '
						
						<li class="memberOverviewBlock">
							<h3 class="block-textHeader">
								<a href="#" class="memberOverViewBlock-title">' . 'Most Solved issues sites' . '</a>
							</h3>
							<ol class="memberOverviewBlock-list">
								' . $__compilerTemp1 . '
							</ol>
						</li>
						
						<li class="memberOverviewBlock">
							<h3 class="block-textHeader">
								<a href="#" class="memberOverViewBlock-title">' . 'Most Unsolved issues sites' . '</a>
							</h3>
							<ol class="memberOverviewBlock-list">
								' . $__compilerTemp2 . '
							</ol>
						</li>
						
						<li class="memberOverviewBlock">
							<h3 class="block-textHeader">
								<a href="#" class="memberOverViewBlock-title">' . 'Most Complains sites' . '</a>
							</h3>
							<ol class="memberOverviewBlock-list">
								' . $__compilerTemp3 . '
							</ol>
						</li>
						
					';
	}
	$__finalCompiled .= '
				</ol>
			</div>
		</div>
</section>



';
	return $__finalCompiled;
}
);