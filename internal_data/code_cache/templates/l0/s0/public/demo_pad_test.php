<?php
// FROM HASH: 3eaf18c26bac353359618d4bf8fd658c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Demo pad index page');
	$__finalCompiled .= '

<div class="block">
    <div class="block-container">
        <div class="block-body">
            ';
	if ($__templater->isTraversable($__vars['posts'])) {
		foreach ($__vars['posts'] AS $__vars['post']) {
			$__finalCompiled .= '
                <div class="block-row">
                    <ul class="listInline listInline--bullet">
                        <li>Post ID = ' . $__templater->escape($__vars['post']['post_id']) . '</li>
                        <li>Thread - ' . $__templater->escape($__vars['post']['Thread']['title']) . '</li>
                        <li>Username = ' . $__templater->escape($__vars['post']['username']) . '</li>
                        <li>Location = ' . $__templater->escape($__vars['post']['User']['Profile']['location']) . '</li>
                        <li>Email address = ' . $__templater->escape($__vars['post']['User']['email']) . '</li>
                    </ul>
                </div>
            ';
		}
	}
	$__finalCompiled .= '
            <!-- <div class="block-row">
                <p>Here is some content</p>
                <p>Here is some more content</p>
                <p>
                    <a href="' . $__templater->func('link', array('notes', ), true) . '">Back to the index page</a>
                </p>
            </div> -->
        </div>
    </div>
</div>';
	return $__finalCompiled;
}
);