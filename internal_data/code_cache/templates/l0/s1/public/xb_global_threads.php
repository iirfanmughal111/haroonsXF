<?php
// FROM HASH: b2ec417cb58e9d99eafde316d35c41d7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['global_threads']) {
		$__finalCompiled .= '	
<div class="block">
		<div class="block-container">
			<div class="block-body">
		
				<h2 class="block-header">
				' . 'Global Threads' . '
				
			</h2>
				
				
				<div class="structItemContainer-group structItemContainer-group--sticky">
							';
		if ($__templater->isTraversable($__vars['global_threads'])) {
			foreach ($__vars['global_threads'] AS $__vars['thread']) {
				$__finalCompiled .= '
								' . $__templater->callMacro('thread_list_macros', 'item', array(
					'thread' => $__vars['thread'],
					'forum' => $__vars['forum'],
				), $__vars) . '
							';
			}
		}
		$__finalCompiled .= '
						</div>
				
			</div>
	</div>
	</div>
	
';
	}
	return $__finalCompiled;
}
);