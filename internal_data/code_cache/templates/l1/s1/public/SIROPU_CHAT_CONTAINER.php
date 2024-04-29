<?php
// FROM HASH: 6f4106fdcf3b3eca76e4140a5c1fa50c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<!DOCTYPE html>
<html id="XF" lang="' . $__templater->escape($__vars['xf']['language']['language_code']) . '" dir="' . $__templater->escape($__vars['xf']['language']['text_direction']) . '"
	data-app="public"
	data-template="' . $__templater->escape($__vars['template']) . '"
	data-container-key="' . $__templater->escape($__vars['containerKey']) . '"
	data-content-key="' . $__templater->escape($__vars['contentKey']) . '"
	data-logged-in="' . ($__vars['xf']['visitor']['user_id'] ? 'true' : 'false') . '"
	data-cookie-prefix="' . $__templater->escape($__vars['xf']['cookie']['prefix']) . '"
	class="has-no-js' . ($__vars['template'] ? (' template-' . $__templater->escape($__vars['template'])) : '') . '"
	' . ($__vars['xf']['runJobs'] ? ' data-run-jobs=""' : '') . '>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1">

		';
	$__vars['siteName'] = $__vars['xf']['options']['boardTitle'];
	$__finalCompiled .= '
		';
	$__vars['h1'] = $__templater->preEscaped($__templater->func('page_h1', array($__vars['siteName'])));
	$__finalCompiled .= '
		';
	$__vars['description'] = $__templater->preEscaped($__templater->func('page_description'));
	$__finalCompiled .= '

		<title>' . $__templater->func('page_title', array('%s | %s', $__vars['xf']['options']['boardTitle'], $__vars['pageNumber'])) . '</title>

		';
	if ($__templater->isTraversable($__vars['head'])) {
		foreach ($__vars['head'] AS $__vars['headTag']) {
			$__finalCompiled .= '
			' . $__templater->escape($__vars['headTag']) . '
		';
		}
	}
	$__finalCompiled .= '

		';
	if ((!$__vars['head']['meta_site_name']) AND !$__templater->test($__vars['siteName'], 'empty', array())) {
		$__finalCompiled .= '
			' . $__templater->callMacro('metadata_macros', 'site_name', array(
			'siteName' => $__vars['siteName'],
			'output' => true,
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
		';
	if (!$__vars['head']['meta_title']) {
		$__finalCompiled .= '
			' . $__templater->callMacro('metadata_macros', 'title', array(
			'title' => ($__templater->func('page_title', array(), false) ?: $__vars['siteName']),
			'output' => true,
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
		';
	if ((!$__vars['head']['meta_description']) AND (!$__templater->test($__vars['description'], 'empty', array()) AND $__vars['pageDescriptionMeta'])) {
		$__finalCompiled .= '
			' . $__templater->callMacro('metadata_macros', 'description', array(
			'description' => $__vars['description'],
			'output' => true,
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
		';
	if ((!$__vars['head']['meta_image_url']) AND $__templater->func('property', array('publicMetadataLogoUrl', ), false)) {
		$__finalCompiled .= '
			' . $__templater->callMacro('metadata_macros', 'image_url', array(
			'imageUrl' => $__templater->func('base_url', array($__templater->func('property', array('publicMetadataLogoUrl', ), false), true, ), false),
			'output' => true,
		), $__vars) . '
		';
	}
	$__finalCompiled .= '

		';
	if ($__templater->func('property', array('metaThemeColor', ), false)) {
		$__finalCompiled .= '
			<meta name="theme-color" content="' . $__templater->func('property', array('metaThemeColor', ), true) . '" />
		';
	}
	$__finalCompiled .= '

		' . $__templater->callMacro('helper_js_global', 'head', array(
		'app' => 'public',
	), $__vars) . '

		';
	if ($__templater->func('property', array('publicFaviconUrl', ), false)) {
		$__finalCompiled .= '
			<link rel="icon" type="image/png" href="' . $__templater->func('base_url', array($__templater->func('property', array('publicFaviconUrl', ), false), ), true) . '" sizes="32x32" />
		';
	}
	$__finalCompiled .= '
		';
	if ($__templater->func('property', array('publicMetadataLogoUrl', ), false)) {
		$__finalCompiled .= '
			<link rel="apple-touch-icon" href="' . $__templater->func('base_url', array($__templater->func('property', array('publicMetadataLogoUrl', ), false), true, ), true) . '" />
		';
	}
	$__finalCompiled .= '
		' . $__templater->includeTemplate('google_analytics', $__vars) . '
	</head>
	<body id="siropuChatFullPage" data-template="' . $__templater->escape($__vars['template']) . '">
			' . $__templater->filter($__vars['content'], array(array('raw', array()),), true) . '
			<div class="u-bottomFixer js-bottomFixTarget">
				';
	if ($__vars['notices']['floating']) {
		$__finalCompiled .= '
					' . $__templater->callMacro('notice_macros', 'notice_list', array(
			'type' => 'floating',
			'notices' => $__vars['notices']['floating'],
		), $__vars) . '
				';
	}
	$__finalCompiled .= '
			</div>
			' . $__templater->callMacro('helper_js_global', 'body', array(
		'app' => 'public',
		'jsState' => $__vars['jsState'],
	), $__vars) . '

			';
	if (($__templater->func('count', array($__vars['xf']['reactionsActive'], ), false) > 1) AND $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
				<script type="text/template" id="xfReactTooltipTemplate">
					<div class="tooltip-content-inner">
						<div class="reactTooltip">
							';
		if ($__templater->isTraversable($__vars['xf']['reactionsActive'])) {
			foreach ($__vars['xf']['reactionsActive'] AS $__vars['reactionId'] => $__vars['reaction']) {
				$__finalCompiled .= '
								' . $__templater->func('reaction', array(array(
					'id' => $__vars['reactionId'],
					'tooltip' => 'true',
				))) . '
							';
			}
		}
		$__finalCompiled .= '
						</div>
					</div>
				</script>
			';
	}
	$__finalCompiled .= '

			' . $__templater->filter($__vars['ldJsonHtml'], array(array('raw', array()),), true) . '
	</body>
</html>';
	return $__finalCompiled;
}
);