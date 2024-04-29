<?php
// FROM HASH: 42de07642fc5e9a03d395a0c1ceb2409
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Showcase trophy select');
	$__finalCompiled .= '

';
	$__templater->includeCss('thuserimprovements_trophy_showcase_macros.less');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['trophies'])) {
		foreach ($__vars['trophies'] AS $__vars['trophy']) {
			$__compilerTemp1 .= '
					';
			$__compilerTemp2 = '';
			if ($__vars['trophy']['Trophy']['th_icon_type'] === 'fa') {
				$__compilerTemp2 .= '
									<i class="fa fa-' . $__templater->escape($__vars['trophy']['Trophy']['th_icon_value']) . ' ' . $__templater->escape($__vars['trophy']['Trophy']['th_icon_value']) . '"></i>
									';
			} else if ($__vars['trophy']['Trophy']['th_icon_type'] === 'image') {
				$__compilerTemp2 .= '
									<img src="' . $__templater->escape($__vars['trophy']['Trophy']['th_icon_value']) . '"/>
									';
			} else {
				$__compilerTemp2 .= '
									' . $__templater->escape($__vars['trophy']['Trophy']['trophy_points']) . '
								';
			}
			$__compilerTemp1 .= $__templater->dataRow(array(
			), array(array(
				'class' => 'dataList-cell--first',
				'_type' => 'cell',
				'html' => '
							' . $__templater->formCheckBox(array(
				'name' => 'trophy_ids[]',
			), array(array(
				'value' => $__vars['trophy']['trophy_id'],
				'id' => 'trophy-' . $__vars['trophy']['trophy_id'],
				'class' => 'kl-trophy-select',
				'selected' => $__vars['trophy']['th_showcased'],
				'_type' => 'option',
			))) . '
						',
			),
			array(
				'class' => 'dataList-cell--imageSmall',
				'style' => 'width: 48px;',
				'_type' => 'cell',
				'html' => '
							<a href="' . $__templater->func('link', array('trophies', $__vars['trophy']['Trophy'], ), true) . '" class="trophyIconItem" title="' . $__templater->escape($__vars['trophy']['Trophy']['title']) . '" data-xf-init="tooltip">
								' . $__compilerTemp2 . '
							</a>
						',
			),
			array(
				'class' => 'dataList-cell--link dataList-cell--main',
				'hash' => $__vars['character']['character_id'],
				'style' => 'width: 100%',
				'_type' => 'cell',
				'html' => '
							<label for="trophy-' . $__templater->escape($__vars['trophy']['trophy_id']) . '">
								<div class="dataList-mainRow">' . $__templater->escape($__vars['trophy']['Trophy']['title']) . '</div>
							</label>
						',
			))) . '
				';
		}
	}
	$__compilerTemp3 = '';
	if (!$__vars['unlimited']) {
		$__compilerTemp3 .= '/<span class="max-amount">' . $__templater->escape($__vars['amount']) . '</span>';
	}
	$__templater->inlineJs('
			var selectedAmount = ' . $__vars['amountSelected'] . ',
			maxAmount = ' . $__vars['amount'] . ';

			$(\'.kl-trophy-select\').on(\'change\', function(event) {
			if($(event.currentTarget).is(\':checked\')) {
			selectedAmount += 1;
			}
			else {
			selectedAmount -= 1;
			}

			$(\'.selected-amount\').html(selectedAmount);

			if(selectedAmount > maxAmount) {
			$(\'.block-footer .selected-amount\').addClass(\'error\');
			}
			else {
			$(\'.block-footer .selected-amount\').removeClass(\'error\');
			}
			});
		');
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->dataList('
				' . $__compilerTemp1 . '
			', array(
	)) . '
		</div>
		<div class="block-footer">
			' . 'Trophies selected' . $__vars['xf']['language']['label_separator'] . ' <span class="selected-amount">' . $__templater->escape($__vars['amountSelected']) . '</span>' . $__compilerTemp3 . '
		</div>
		' . '' . '
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('members/thui-trophies/showcase-select', $__vars['xf']['visitor'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);