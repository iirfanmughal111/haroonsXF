<?php
// FROM HASH: d12df9cca217799604d7441b64906dfc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('xfmg_album_view.less');
	$__finalCompiled .= '
';
	if ($__vars['report']['content_info']['description']) {
		$__finalCompiled .= '
	<div class="block-row block-row--separated">
		<div class="bbCodeBlock bbCodeBlock--expandable bbCodeBlock--albumDescription">
			<div class="bbCodeBlock-content">
				<div class="bbCodeBlock-expandContent">
					' . $__templater->func('structured_text', array($__vars['report']['content_info']['description'], ), true) . '
				</div>
				<div class="bbCodeBlock-expandLink"><a>' . 'Click to expand...' . '</a></div>
			</div>
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);