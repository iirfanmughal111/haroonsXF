<?php
// FROM HASH: a3110b73f47b30284d74bcdf9f755887
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit notice');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			<h2 class="block-tabHeader tabs hScroller" data-xf-init="tabs h-scroller" role="tablist">
				<span class="hScroller-scroll">
					<a class="tabs-tab is-active" role="tab" tabindex="0" aria-controls="' . $__templater->func('unique_id', array('notice1', ), true) . '">' . 'Notice' . ' 1</a>
					<a class="tabs-tab" role="tab" tabindex="0" aria-controls="' . $__templater->func('unique_id', array('notice2', ), true) . '">' . 'Notice' . ' 2</a>
					<a class="tabs-tab" role="tab" tabindex="0" aria-controls="' . $__templater->func('unique_id', array('notice2', ), true) . '">' . 'Notice' . ' 3</a>
					<a class="tabs-tab" role="tab" tabindex="0" aria-controls="' . $__templater->func('unique_id', array('notice2', ), true) . '">' . 'Notice' . ' 4</a>
					<a class="tabs-tab" role="tab" tabindex="0" aria-controls="' . $__templater->func('unique_id', array('notice2', ), true) . '">' . 'Notice' . ' 5</a>
				</span>
			</h2>
			<ul class="tabPanes">
				<li class="is-active" role="tabpanel" id="' . $__templater->func('unique_id', array('notice1', ), true) . '">
					' . $__templater->formEditorRow(array(
		'name' => 'notice[1]',
		'value' => $__vars['xf']['options']['siropuChatNotice']['0'],
	), array(
		'rowtype' => 'fullWidth noLabel',
	)) . '
				</li>
				<li class="is-active" role="tabpanel" id="' . $__templater->func('unique_id', array('notice2', ), true) . '">
					' . $__templater->formEditorRow(array(
		'name' => 'notice[2]',
		'value' => $__vars['xf']['options']['siropuChatNotice']['1'],
	), array(
		'rowtype' => 'fullWidth noLabel',
	)) . '
				</li>
				<li class="is-active" role="tabpanel" id="' . $__templater->func('unique_id', array('notice3', ), true) . '">
					' . $__templater->formEditorRow(array(
		'name' => 'notice[3]',
		'value' => $__vars['xf']['options']['siropuChatNotice']['2'],
	), array(
		'rowtype' => 'fullWidth noLabel',
	)) . '
				</li>
				<li class="is-active" role="tabpanel" id="' . $__templater->func('unique_id', array('notice4', ), true) . '">
					' . $__templater->formEditorRow(array(
		'name' => 'notice[4]',
		'value' => $__vars['xf']['options']['siropuChatNotice']['3'],
	), array(
		'rowtype' => 'fullWidth noLabel',
	)) . '
				</li>
				<li class="is-active" role="tabpanel" id="' . $__templater->func('unique_id', array('notice5', ), true) . '">
					' . $__templater->formEditorRow(array(
		'name' => 'notice[5]',
		'value' => $__vars['xf']['options']['siropuChatNotice']['4'],
	), array(
		'rowtype' => 'fullWidth noLabel',
	)) . '
				</li>
			</ul>
		</div>
	</div>
	' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'class' => 'js-overlayClose',
	), array(
		'rowtype' => 'simple',
		'html' => '
			' . $__templater->formCheckBox(array(
		'name' => 'remove_all',
		'standalone' => 'true',
	), array(array(
		'value' => '1',
		'label' => 'Remove notice',
		'_type' => 'option',
	))) . '
		',
	)) . '
', array(
		'action' => $__templater->func('link', array('chat/edit-notice', ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-xf-init' => 'siropu-chat-edit-notice',
	));
	return $__finalCompiled;
}
);