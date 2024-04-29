<?php
// FROM HASH: 20302765abdedcc8ec164b04b1bd502e
return array(
'macros' => array('review_simple' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'review' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('message.less');
	$__finalCompiled .= '
	
	<span class="u-anchorTarget" id="item-review-' . $__templater->escape($__vars['review']['item_rating_id']) . '"></span>
	<div class="contentRow">
		<div class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['review']['User'], 'xxs', false, array(
	))) . '
		</div>
		<div class="contentRow-main contentRow-main--close">
			<div class="contentRow-lesser">
				' . $__templater->callMacro('rating_macros', 'stars', array(
		'rating' => $__vars['review']['rating'],
		'class' => 'ratingStars--smaller',
	), $__vars) . ' 
			</div>
			<div class="contentRow-minor contentRow-minor--smaller">
				<ul class="listInline listInline--bullet">
					<li>
							' . ($__templater->escape($__vars['review']['User']['username']) ?: 'Deleted member') . '
					</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['review']['rating_date'], array(
	))) . '</li>
				</ul>
			</div>
			
			';
	if ($__vars['review']['rating_state'] == 'deleted') {
		$__finalCompiled .= '
				<div class="messageNotice messageNotice--deleted">
					' . $__templater->callMacro('deletion_macros', 'notice', array(
			'log' => $__vars['review']['DeletionLog'],
		), $__vars) . '
				</div>
			';
	}
	$__finalCompiled .= '
			<div class="contentRow-lesser">' . $__templater->func('bb_code', array($__vars['review']['message'], 'message', $__vars['review'], ), true) . '</div>
			
			';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '

						';
	$__vars['hasActionBarMenu'] = false;
	$__compilerTemp1 .= '
						';
	if ($__templater->method($__vars['review'], 'canDelete', array('soft', ))) {
		$__compilerTemp1 .= '
							<a href="' . $__templater->func('link', array('bh_brands/review/delete', $__vars['review'], ), true) . '"
							   class="actionBar-action actionBar-action--delete actionBar-action--menuItem"
							   data-xf-click="overlay">
								' . 'Delete' . '
							</a>
							';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp1 .= '
						';
	}
	$__compilerTemp1 .= '
						';
	if (($__vars['review']['rating_state'] == 'deleted') AND $__templater->method($__vars['review'], 'canUndelete', array())) {
		$__compilerTemp1 .= '
							<a href="' . $__templater->func('link', array('bh_brands/review/undelete', $__vars['review'], ), true) . '" data-xf-click="overlay"
							   class="actionBar-action actionBar-action--undelete actionBar-action--menuItem">
								' . 'Undelete' . '
							</a>
							';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp1 .= '
						';
	}
	$__compilerTemp1 .= '
					';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
				<div class="actionBar-set actionBar-set--internal">
					' . $__compilerTemp1 . '
				</div>
			';
	}
	$__finalCompiled .= '
						
		</div>
	</div>

';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);