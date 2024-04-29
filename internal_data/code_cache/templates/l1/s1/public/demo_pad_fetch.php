<?php
// FROM HASH: 7d25df1062f3574dc07249ba85072276
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->isTraversable($__vars['posts'])) {
		foreach ($__vars['posts'] AS $__vars['post']) {
			$__finalCompiled .= '
                <div class="block-row">
                    <ul class="listInline listInline--bullet">
                        <li>Post ID = ' . $__templater->escape($__vars['post']['note_id']) . '</li>
                        <li>Thread - ' . $__templater->escape($__vars['post']['title']) . '</li>
                    </ul>
                </div>
            ';
		}
	}
	$__finalCompiled .= '




hello';
	return $__finalCompiled;
}
);