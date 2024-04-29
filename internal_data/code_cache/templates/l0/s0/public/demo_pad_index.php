<?php
// FROM HASH: e6c4b9c6a1a68f16d65e4d6499269e15
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Demo pad index page');
	$__finalCompiled .= '

';
	$__templater->includeCss('demo_pad.less');
	$__finalCompiled .= '

<div class="block">
    <div class="block-container">
        <div class="block-body">
            <div class="block-row">
                <p class="chColor">Hello There!</p>
                <p>
                    <a href="' . $__templater->func('link', array('notes/test', ), true) . '">Open the test page</a>
                </p>
            </div>
				
        </div>
    </div>
</div>';
	return $__finalCompiled;
}
);