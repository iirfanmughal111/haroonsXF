<?php
// FROM HASH: 3893f25c81f360de72ef551fb84fad96
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('');
	$__finalCompiled .= '
<div class="block">
		<div class="block-container">
			<div class="block-body">
					<div class="block-header">
						<h3 class="block-minorHeader">' . 'My ' . $__templater->escape($__vars['item']['Brand']['brand_title']) . ' ' . $__templater->escape($__vars['item']['item_title']) . ' Topics' . '</h3>
						<div class="p-description">' . 'Here are the most recent ' . $__templater->escape($__vars['item']['item_title']) . ' topics from our community.' . '</div>
					</div>
					<div class="structItemContainer-group js-threadList">
												';
	if (!$__templater->test($__vars['threads'], 'empty', array())) {
		$__finalCompiled .= '
													';
		if ($__templater->isTraversable($__vars['threads'])) {
			foreach ($__vars['threads'] AS $__vars['thread']) {
				$__finalCompiled .= '
														' . $__templater->callMacro('thread_list_macros', 'item', array(
					'thread' => $__vars['thread'],
				), $__vars) . '
													';
			}
		}
		$__finalCompiled .= '

												';
	} else {
		$__finalCompiled .= '
													<div class="blockMessage">' . 'No results found.' . '</div>
												';
	}
	$__finalCompiled .= '
						
					</div>
			</div>
		</div>
</div>';
	return $__finalCompiled;
}
);