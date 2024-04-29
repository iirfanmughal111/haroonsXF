<?php
// FROM HASH: 0d00c0e4090226929fc419cf7b44d670
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('fs_hideContent_with_preview.less');
	$__finalCompiled .= '
<div class="widget-block">
	<img src="' . ($__templater->escape($__vars['thumbnailUrl']) ?: $__templater->func('base_url', array('styles/fs/hcwp/preview.jpg', ), true)) . '" alt="Preview image" loading="lazy">
	
	<div class="widget-content">
		<div class="widget-title">' . 'This content is hidden' . '</div>
		<div class="widget-description">' . 'Only registered members can access it.' . '</div>
		' . $__templater->button('<i class="fas fa-lock"></i> ' . 'Get instant access' . ' ', array(
		'href' => $__vars['xf']['options']['fs_hcwp_btnUrl'],
		'class' => 'widget-button',
		'overlay' => 'true',
	), '', array(
	)) . '
	</div>
</div>';
	return $__finalCompiled;
}
);