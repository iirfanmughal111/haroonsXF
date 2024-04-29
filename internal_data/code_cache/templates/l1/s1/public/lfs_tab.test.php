<?php
// FROM HASH: d6604ab62d72453980995909cb8c01e5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<ul class="content ' . $__templater->escape($__vars['tab']['tab_class']) . '">
    ';
	if (!$__templater->test($__vars['threads'], 'empty', array())) {
		$__finalCompiled .= '
        ';
		if ($__templater->isTraversable($__vars['threads'])) {
			foreach ($__vars['threads'] AS $__vars['thread']) {
				$__finalCompiled .= '
            <li class="block-row structItem--lfsItem" id="js-lfsItemThread-' . $__templater->escape($__vars['thread']['thread_id']) . '"
                data-date="' . $__templater->escape($__vars['thread']['post_date']) . '">
                ' . $__templater->callMacro('thread_list_macros', 'item_new_threads', array(
					'thread' => $__vars['thread'],
				), $__vars) . '
            </li>
        ';
			}
		}
		$__finalCompiled .= '

        ';
	} else {
		$__finalCompiled .= '

        ' . $__templater->callMacro('lfs_macros', 'empty_block', array(), $__vars) . '

    ';
	}
	$__finalCompiled .= '
</ul>

' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'lfs/tab',
		'params' => array('tab_id' => $__vars['tab']['tab_id'], ),
		'wrapperclass' => 'hidden-pageNav js-lfsPageNav',
		'perPage' => $__vars['perPage'],
	)));
	return $__finalCompiled;
}
);