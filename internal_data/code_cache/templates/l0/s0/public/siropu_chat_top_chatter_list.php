<?php
// FROM HASH: 42b034c2e8be169a44684d6add37bab5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Top Chatters');
	$__finalCompiled .= '

<div class="tabs tabs--standalone">
	<div class="hScroller" data-xf-init="h-scroller">
		<span class="hScroller-scroll">
			<a href="' . $__templater->func('link', array('chat/top-chatters/', ), true) . '" class="tabs-tab' . (($__vars['from'] == '') ? ' is-active' : '') . '">' . 'All time' . '</a>
			<a href="' . $__templater->func('link', array('chat/top-chatters/', null, array('from' => 'today', ), ), true) . '" class="tabs-tab' . (($__vars['from'] == 'today') ? ' is-active' : '') . '">' . 'Today' . '</a>
			<a href="' . $__templater->func('link', array('chat/top-chatters/', null, array('from' => 'yesterday', ), ), true) . '" class="tabs-tab' . (($__vars['from'] == 'yesterday') ? ' is-active' : '') . '">' . 'Yesterday' . '</a>
			<a href="' . $__templater->func('link', array('chat/top-chatters/', null, array('from' => 'thisWeek', ), ), true) . '" class="tabs-tab' . (($__vars['from'] == 'thisWeek') ? ' is-active' : '') . '">' . 'This week' . '</a>
			<a href="' . $__templater->func('link', array('chat/top-chatters/', null, array('from' => 'thisMonth', ), ), true) . '" class="tabs-tab' . (($__vars['from'] == 'thisMonth') ? ' is-active' : '') . '">' . 'This month' . '</a>
			<a href="' . $__templater->func('link', array('chat/top-chatters/', null, array('from' => 'lastWeek', ), ), true) . '" class="tabs-tab' . (($__vars['from'] == 'lastWeek') ? ' is-active' : '') . '">' . 'Last week' . '</a>
			<a href="' . $__templater->func('link', array('chat/top-chatters/', null, array('from' => 'lastMonth', ), ), true) . '" class="tabs-tab' . (($__vars['from'] == 'lastMonth') ? ' is-active' : '') . '">' . 'Last month' . '</a>
		</span>
	</div>
</div>

<div class="block">
    <div class="block-container">
		<ol class="block-body">
			';
	if (!$__templater->test($__vars['topChatters'], 'empty', array())) {
		$__finalCompiled .= '
				';
		if ($__templater->isTraversable($__vars['topChatters'])) {
			foreach ($__vars['topChatters'] AS $__vars['user']) {
				$__finalCompiled .= '
					<li class="block-row block-row--separated">
						' . $__templater->callMacro('member_list_macros', 'item', array(
					'user' => $__vars['user'],
					'extraData' => $__vars['user']['siropu_chat_message_count'],
					'extraDataBig' => true,
				), $__vars) . '
					</li>
				';
			}
		}
		$__finalCompiled .= '
				';
	} else {
		$__finalCompiled .= '
				<li class="block-row">' . 'There are no users in the top.' . '</li>
			';
	}
	$__finalCompiled .= '
		</ol>
		<div class="block-footer">
			' . 'Last update' . ': ' . $__templater->func('date_dynamic', array($__vars['lastUpdate'], array(
	))) . '
		</div>
    </div>
</div>';
	return $__finalCompiled;
}
);