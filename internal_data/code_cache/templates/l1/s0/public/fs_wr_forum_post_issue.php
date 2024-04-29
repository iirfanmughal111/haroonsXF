<?php
// FROM HASH: c660fc74414379a61c034b7cadd5c736
return array(
'macros' => array('type_chooser' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'thread' => '!',
		'forum' => '!',
		'creatableThreadTypes' => '!',
		'defaultThreadType' => '!',
		'rowType' => 'fullWidth noLabel noTopPadding noBottomPadding mergeNext mergePrev',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if ($__templater->func('count', array($__vars['creatableThreadTypes'], ), false) > 1) {
		$__finalCompiled .= '
		';
		$__templater->includeCss('input_extended.less');
		$__finalCompiled .= '
		';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['creatableThreadTypes'])) {
			foreach ($__vars['creatableThreadTypes'] AS $__vars['threadTypeId'] => $__vars['threadType']) {
				$__compilerTemp1 .= '
							<li role="none">
								<label class="inputTypes-type">
									<input type="radio" name="discussion_type" value="' . $__templater->escape($__vars['threadTypeId']) . '"
										class="inputTypes-input"
										data-xf-init="disabler"
										data-hide="true"
										data-optional="true"
										data-autofocus="false"
										data-container=".js-threadTypeData[data-type-id=\'' . $__templater->escape($__vars['threadTypeId']) . '\']"
										' . (($__vars['threadTypeId'] == $__vars['defaultThreadType']) ? 'checked="checked"' : '') . '
									/>
									<span class="inputTypes-display inputTypes-display--type_' . $__templater->escape($__vars['threadTypeId']) . '">
										<span class="inputTypes-icon" aria-hidden="true">
											';
				$__vars['typeIcon'] = $__templater->method($__vars['threadType'], 'getTypeIconClass', array());
				$__compilerTemp1 .= '
											';
				if ($__vars['typeIcon']) {
					$__compilerTemp1 .= '
												' . $__templater->fontAwesome($__templater->escape($__vars['typeIcon']), array(
					)) . '
											';
				} else {
					$__compilerTemp1 .= '
												<i class="inputTypes-defaultIcon"></i>
											';
				}
				$__compilerTemp1 .= '
										</span>
										<span class="inputTypes-title">' . $__templater->escape($__templater->method($__vars['threadType'], 'getTypeTitle', array())) . '</span>
									</span>
								</label>
							</li>
						';
			}
		}
		$__finalCompiled .= $__templater->formRow('
			<div class="hScroller inputTypesScroller" data-xf-init="h-scroller">
				<div class="hScroller-scroll">
					<ul class="inputTypes" role="radiogroup" aria-label="' . $__templater->filter('thread_type', array(array('for_attr', array()),), true) . '">
						' . $__compilerTemp1 . '
					</ul>
				</div>
			</div>
		', array(
			'rowtype' => $__vars['rowType'],
		)) . '
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->formHiddenVal('discussion_type', $__vars['defaultThreadType'], array(
		)) . '
	';
	}
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
),
'type_fields' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'thread' => '!',
		'forum' => '!',
		'creatableThreadTypes' => '!',
		'defaultThreadType' => '!',
		'subContext' => '!',
		'extraOptions' => array(),
		'draftOverride' => null,
		'formRowSepVariant' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
			';
	if ($__templater->isTraversable($__vars['creatableThreadTypes'])) {
		foreach ($__vars['creatableThreadTypes'] AS $__vars['threadTypeId'] => $__vars['threadType']) {
			$__compilerTemp1 .= '
				';
			$__compilerTemp2 = '';
			$__compilerTemp2 .= '
							' . $__templater->filter($__templater->method($__vars['threadType'], 'renderExtraDataEdit', array($__vars['thread'], 'create', $__vars['subContext'], array('draft' => (($__vars['subContext'] == 'quick') ? null : $__vars['forum']->{'draft_thread'}), 'draftOverride' => $__vars['draftOverride'], ) + $__vars['extraOptions'], )), array(array('raw', array()),), true) . '
						';
			if (strlen(trim($__compilerTemp2)) > 0) {
				$__compilerTemp1 .= '
					<li class="js-threadTypeData" data-type-id="' . $__templater->escape($__vars['threadTypeId']) . '" style="' . (($__vars['threadTypeId'] != $__vars['defaultThreadType']) ? 'display:none;' : '') . '">
						<hr class="formRowSep ' . $__templater->escape($__vars['formRowSepVariant']) . '" />
						' . $__compilerTemp2 . '
					</li>
				';
			}
			$__compilerTemp1 .= '
			';
		}
	}
	$__compilerTemp1 .= '
		';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<ul class="listPlain">
		' . $__compilerTemp1 . '
		</ul>
	';
	}
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Create Issue');
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['forum'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__vars['titleFinalHtml'] = $__templater->preEscaped('');
	$__compilerTemp1 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp1 .= '
						' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
			'forceHash' => $__vars['forum']['draft_thread']['attachment_hash'],
		), $__vars) . '
					';
	}
	$__compilerTemp2 = '';
	if ($__vars['xf']['options']['multiQuote']) {
		$__compilerTemp2 .= '
						' . $__templater->callMacro('multi_quote_macros', 'button', array(
			'href' => $__templater->func('link', array('threads/multi-quote', $__vars['thread'], ), false),
			'messageSelector' => '.js-post',
			'storageKey' => 'multiQuoteThread',
		), $__vars) . '
					';
	}
	$__finalCompiled .= $__templater->form('

	<div class="block-container">
		<div class="block-body">

			' . '' . '

			' . $__templater->formPrefixInputRow($__vars['prefixes'], array(
		'type' => 'thread',
		'rows' => '1',
		'prefix-value' => ($__vars['forum']['draft_thread']['prefix_id'] ?: ($__vars['thread']['prefix_id'] ?: $__vars['forum']['default_prefix_id'])),
		'textbox-value' => (($__vars['title'] ?: $__vars['thread']['title']) ?: $__vars['forum']['draft_thread']['title']),
		'textbox-class' => 'input--title',
		'placeholder' => 'Issue title',
		'autofocus' => 'autofocus',
		'maxlength' => $__templater->func('max_length', array('XF:Thread', 'title', ), false),
		'help-href' => $__templater->func('link', array('forums/prefix-help', $__vars['forum'], ), false),
	), array(
		'label' => 'Title',
		'rowtype' => 'fullWidth noLabel',
		'finalhtml' => $__templater->escape($__vars['titleFinalHtml']),
	)) . '

			' . '

			' . $__templater->callMacro(null, 'type_chooser', array(
		'thread' => $__vars['thread'],
		'forum' => $__vars['forum'],
		'creatableThreadTypes' => $__vars['creatableThreadTypes'],
		'defaultThreadType' => $__vars['defaultThreadType'],
	), $__vars) . '

			<div class="js-inlineNewPostFields">
				' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => ($__vars['post']['message'] ?: $__vars['forum']['draft_thread']['message']),
		'attachments' => ($__vars['attachmentData'] ? $__vars['attachmentData']['attachments'] : array()),
		'data-preview-url' => $__templater->func('link', array('forums/thread-preview', $__vars['forum'], ), false),
	), array(
		'rowtype' => 'fullWidth noLabel mergePrev',
		'label' => 'Message',
	)) . '

				' . $__templater->formRow('
					' . $__compilerTemp1 . '

					' . $__compilerTemp2 . '
				', array(
		'rowtype' => 'fullWidth noLabel mergePrev noTopPadding',
	)) . '
				
			</div>
		</div>

		' . $__templater->formSubmitRow(array(
		'submit' => 'Create Issue',
		'icon' => 'write',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('forums/post-thread', $__vars['forum'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-xf-init' => 'attachment-manager',
		'draft' => $__templater->func('link', array('forums/draft', $__vars['forum'], ), false),
	)) . '




' . '

';
	return $__finalCompiled;
}
);