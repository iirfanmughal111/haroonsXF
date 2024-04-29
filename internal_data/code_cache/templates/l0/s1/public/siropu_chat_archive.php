<?php
// FROM HASH: fe0dcc247e17f824a1ffef9df5456e16
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Archive');
	$__finalCompiled .= '

';
	$__templater->includeCss('siropu_chat.less');
	$__finalCompiled .= '
';
	$__templater->includeCss('siropu_chat_archive.less');
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'siropu/chat/core.min.js',
	));
	$__templater->inlineJs('
	$(function()
	{
		var target = $(\'.siropuChatMessageRow:target\');
		var sticky = $(\'.p-navSticky\');

		if (target.length)
		{
			if (sticky.length)
			{
				$(document).scrollTop(target.offset().top - sticky.height());
			}

			if (!target.hasClass(\'siropuChatMention\'))
			{
				target.addClass(\'siropuChatMention siropuChatTarget\');
			}
		}
	});
');
	$__finalCompiled .= '

';
	$__vars['canMassDelete'] = $__templater->method($__vars['xf']['visitor'], 'canPruneSiropuChatMessages', array());
	$__finalCompiled .= '

' . $__templater->callMacro('lightbox_macros', 'setup', array(
		'canViewAttachments' => true,
	), $__vars) . '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['xf']['visitor'], 'canSearchSiropuChatArchive', array())) {
		$__compilerTemp1 .= '
			<div class="block-filterBar">
				<div class="filterBar">
						';
		$__compilerTemp2 = '';
		if (!$__templater->test($__vars['rooms'], 'empty', array())) {
			$__compilerTemp2 .= '
									';
			$__compilerTemp3 = array(array(
				'label' => 'Any room',
				'_type' => 'option',
			));
			if ($__templater->isTraversable($__vars['rooms'])) {
				foreach ($__vars['rooms'] AS $__vars['room']) {
					$__compilerTemp3[] = array(
						'value' => $__vars['room']['room_id'],
						'label' => $__templater->escape($__vars['room']['room_name']),
						'_type' => 'option',
					);
				}
			}
			$__compilerTemp2 .= $__templater->formSelect(array(
				'name' => 'room_id',
				'value' => $__vars['params']['room_id'],
			), $__compilerTemp3) . '
									<span class="inputGroup-splitter"></span>
								';
		}
		$__compilerTemp4 = array(array(
			'label' => 'Date presets' . $__vars['xf']['language']['label_separator'],
			'_type' => 'option',
		));
		$__compilerTemp4[] = array(
			'_type' => 'optgroup',
			'options' => array(),
		);
		end($__compilerTemp4); $__compilerTemp5 = key($__compilerTemp4);
		$__compilerTemp4[$__compilerTemp5]['options'] = $__templater->mergeChoiceOptions($__compilerTemp4[$__compilerTemp5]['options'], $__vars['datePresets']);
		$__compilerTemp4[$__compilerTemp5]['options'][] = array(
			'value' => '1995-01-01',
			'label' => 'All time',
			'_type' => 'option',
		);
		$__compilerTemp1 .= $__templater->form('
							<div class="inputGroup inputGroup--auto">
								' . $__compilerTemp2 . '
								' . $__templater->formTextBox(array(
			'name' => 'keywords',
			'value' => $__vars['params']['keywords'],
			'placeholder' => 'Has keywords',
		)) . '
								<span class="inputGroup-splitter"></span>
								' . $__templater->formTextBox(array(
			'name' => 'users',
			'value' => $__vars['params']['users'],
			'placeholder' => 'Posted by',
			'data-xf-init' => 'auto-complete',
		)) . '
								<span class="inputGroup-splitter"></span>
								' . $__templater->formDateInput(array(
			'name' => 'since_date',
			'value' => ($__vars['params']['since_date'] ? $__templater->func('date', array($__vars['params']['since_date'], 'Y-m-d', ), false) : ''),
			'placeholder' => 'Since date...',
		)) . '
								<span class="inputGroup-splitter"></span>
								' . $__templater->formDateInput(array(
			'name' => 'until_date',
			'value' => ($__vars['params']['until_date'] ? $__templater->func('date', array($__vars['params']['until_date'], 'Y-m-d', ), false) : ''),
			'placeholder' => 'Until date...',
		)) . '
								<span class="inputGroup-splitter"></span>
								' . $__templater->formSelect(array(
			'name' => 'date_preset',
		), $__compilerTemp4) . '
								<span class="inputGroup-splitter"></span>
								' . $__templater->button($__templater->fontAwesome('fa-search', array(
		)), array(
			'type' => 'submit',
			'class' => 'button--longText',
		), '', array(
		)) . '
							</div>
						', array(
			'action' => $__templater->func('link', array('chat/archive', ), false),
		)) . '
					</div>
				</div>
		';
	}
	$__compilerTemp6 = '';
	if (!$__templater->test($__vars['messages'], 'empty', array())) {
		$__compilerTemp6 .= '
			   <ul class="siropuChatRoom siropuChatMessages" data-xf-init="siropu-chat-messages lightbox">
					';
		if ($__templater->isTraversable($__vars['messages'])) {
			foreach ($__vars['messages'] AS $__vars['message']) {
				if ($__templater->method($__vars['message'], 'canView', array())) {
					$__compilerTemp6 .= '
					   	' . $__templater->includeTemplate('siropu_chat_room_message_row', $__vars) . '
				   	';
				}
			}
		}
		$__compilerTemp6 .= '
			   </ul>
			   <div class="block-footer block-footer--split">
				  <span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['messages'], $__vars['total'], ), true) . '</span>
				  <span class="block-footer-controls">
					   ' . $__templater->form('
						   <div class="inputGroup inputGroup--auto">
							   ' . $__templater->formSelect(array(
			'name' => 'set_order',
			'value' => $__vars['order'],
		), array(array(
			'value' => 'newest',
			'label' => 'Newest messages first',
			'_type' => 'option',
		),
		array(
			'value' => 'oldest',
			'label' => 'Oldest messages first',
			'_type' => 'option',
		),
		array(
			'value' => 'likes',
			'label' => 'Most likes first',
			'_type' => 'option',
		))) . '
							   <span class="inputGroup-splitter"></span>
							   ' . $__templater->button('', array(
			'type' => 'submit',
			'icon' => 'save',
		), '', array(
		)) . '
						   </div>
					  ', array(
			'action' => $__templater->func('link', array('chat/archive', ), false),
		)) . '
				   </span>
			   </div>
			 ';
	} else {
		$__compilerTemp6 .= '
			   <div class="block-row">
				   ';
		if ($__templater->test($__vars['rooms'], 'empty', array()) AND $__templater->method($__vars['xf']['visitor'], 'canSearchSiropuChatArchive', array())) {
			$__compilerTemp6 .= '
				   		 ' . 'You haven\'t joined any rooms.' . '
				  	';
		} else {
			$__compilerTemp6 .= '
					    ' . 'No messages have been found.' . '
				   ';
		}
		$__compilerTemp6 .= '
			   </div>
			';
	}
	$__compilerTemp7 = '';
	if ($__vars['canMassDelete']) {
		$__compilerTemp7 .= '
			' . $__templater->formSubmitRow(array(
			'icon' => 'delete',
			'sticky' => 'true',
		), array(
			'rowtype' => 'simple',
			'html' => '
					' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'check-all' => '< .block-container',
			'class' => 'js-checkAll',
			'label' => 'Select all',
			'_type' => 'option',
		))) . '
				',
		)) . '
		';
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
		' . $__compilerTemp1 . '
        <div class="block-body">
           ' . $__compilerTemp6 . '
        </div>
		' . $__compilerTemp7 . '
    </div>
	' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'chat/archive',
		'params' => $__vars['params'],
		'wrapperclass' => 'block-outer block-outer--after',
		'perPage' => $__vars['perPage'],
	))) . '
', array(
		'action' => $__templater->func('link', array('chat/archive', ), false),
		'id' => 'siropuChatArchive',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);