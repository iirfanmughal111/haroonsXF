<?php
// FROM HASH: b1c6f4041a36abd679ef3e7b5662ba84
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit ads');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['xf']['options']['siropuChatAdsAboveEditor'])) {
		foreach ($__vars['xf']['options']['siropuChatAdsAboveEditor'] AS $__vars['value']) {
			$__compilerTemp1 .= '
							<li class="inputGroup block-row">
								' . $__templater->formTextArea(array(
				'name' => 'ads_above_editor[]',
				'value' => $__vars['value'],
				'rows' => '3',
				'placeholder' => 'Paste your ad code',
			)) . '
							</li>
						';
		}
	}
	$__compilerTemp2 = '';
	if ($__templater->isTraversable($__vars['xf']['options']['siropuChatAdsBelowEditor'])) {
		foreach ($__vars['xf']['options']['siropuChatAdsBelowEditor'] AS $__vars['value']) {
			$__compilerTemp2 .= '
							<li class="inputGroup block-row">
								' . $__templater->formTextArea(array(
				'name' => 'ads_below_editor[]',
				'value' => $__vars['value'],
				'rows' => '3',
				'placeholder' => 'Paste your ad code',
			)) . '
							</li>
						';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			<h2 class="block-tabHeader tabs" data-xf-init="tabs" role="tablist">
				<a class="tabs-tab is-active" role="tab" tabindex="0" aria-controls="' . $__templater->func('unique_id', array('adsAboveEditor', ), true) . '">' . 'Ads Above Editor' . '</a>
				<a class="tabs-tab" role="tab" tabindex="0" aria-controls="' . $__templater->func('unique_id', array('adsBelowEditor', ), true) . '">' . 'Ads Below Editor' . '</a>
			</h2>

			<ul class="tabPanes">
				<li class="is-active" role="tabpanel" id="' . $__templater->func('unique_id', array('adsAboveEditor', ), true) . '">
					<ul class="listPlain inputGroup-container">
						' . $__compilerTemp1 . '
						<li class="inputGroup block-row" data-xf-init="field-adder" data-increment-format="ads_above_editor[]">
							' . $__templater->formTextArea(array(
		'name' => 'ads_above_editor[]',
		'rows' => '3',
		'placeholder' => 'Paste your ad code',
	)) . '
						</li>
					</ul>
				</li>
				<li class="is-active" role="tabpanel" id="' . $__templater->func('unique_id', array('adsBelowEditor', ), true) . '">
					<ul class="listPlain inputGroup-container">
						' . $__compilerTemp2 . '
						<li class="inputGroup block-row" data-xf-init="field-adder" data-increment-format="ads_below_editor[]">
							' . $__templater->formTextArea(array(
		'name' => 'ads_below_editor[]',
		'rows' => '3',
		'placeholder' => 'Paste your ad code',
	)) . '
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'html' => '
			' . $__templater->formCheckBox(array(
		'name' => 'remove_all',
		'standalone' => 'true',
	), array(array(
		'value' => '1',
		'label' => 'Remove ads',
		'_type' => 'option',
	))) . '
		',
	)) . '
', array(
		'action' => $__templater->func('link', array('chat/edit-ads', ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);