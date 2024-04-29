<?php
// FROM HASH: 7d23f7b86909c2983b5f4a6056a2c9bf
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Embed media');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	$__compilerTemp2 = $__templater->func('xfmg_allowed_media', array('embed', ), false);
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['siteId'] => $__vars['site']) {
			$__compilerTemp1 .= '
						<li><a href="' . $__templater->escape($__vars['site']['site_url']) . '" target="_blank">' . $__templater->escape($__vars['site']['site_title']) . '</a></li>
					';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				<div class="blockMessage blockMessage--error blockMessage--iconic"></div>
			', array(
		'rowclass' => 'u-hidden u-hidden--transition js-pasteError',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'url',
		'type' => 'url',
		'autofocus' => 'autofocus',
		'class' => 'js-pasteInput',
	), array(
		'label' => 'Enter media URL',
	)) . '

			' . $__templater->formRow('

				<ul class="listInline listInline--comma u-smaller">
					' . $__compilerTemp1 . '
				</ul>
				<div class="formRow-explain">
					<a href="' . $__templater->func('link', array('help', array('page_name' => 'bb-codes', ), ), true) . '#media" target="_blank">' . 'Help' . '</a>
				</div>
			', array(
		'label' => 'Approved sites',
		'hint' => 'You may insert media from these sources',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('media/embed-media', null, array('context' => $__vars['context'], ), ), false),
		'class' => 'block',
		'ajax' => 'true',
		'data-xf-init' => 'link-checker',
	));
	return $__finalCompiled;
}
);