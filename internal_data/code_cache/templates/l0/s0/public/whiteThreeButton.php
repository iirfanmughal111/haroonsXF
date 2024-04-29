<?php
// FROM HASH: 175a30969e61820beaf01a08b94030a8
return array(
'macros' => array('link' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '!',
		'confirmUrl' => '!',
		'editText' => 'Remove Bookmark',
		'addText' => 'Bookmark',
		'showText' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
 
	';
	if ($__templater->method($__vars['content'], 'canBookmark', array())) {
		$__finalCompiled .= '

		';
		$__compilerTemp1 = '';
		if ($__templater->method($__vars['content'], 'isBookmarked', array())) {
			$__compilerTemp1 .= $__templater->escape($__vars['editText']);
		} else {
			$__compilerTemp1 .= $__templater->escape($__vars['addText']);
		}
		$__finalCompiled .= $__templater->button(trim('
			<span class="js-bookmarkText">' . $__compilerTemp1 . '</span>
		'), array(
			'href' => $__vars['confirmUrl'],
			'id' => 'white-button',
			'title' => ($__vars['showText'] ? '' : $__templater->filter('Bookmark', array(array('for_attr', array()),), false)),
			'data-xf-click' => 'bookmark-click',
			'data-label' => '.js-bookmarkText',
			'data-sk-bookmarked' => 'addClass:is-bookmarked, ' . $__templater->filter($__vars['editText'], array(array('for_attr', array()),), false),
			'data-sk-bookmarkremoved' => 'removeClass:is-bookmarked, ' . $__templater->filter($__vars['addText'], array(array('for_attr', array()),), false),
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="tab-white-button">
	

				';
	if ($__vars['alreadySub']) {
		$__finalCompiled .= '
				
					 ' . $__templater->button('UnSubsribe', array(
			'id' => 'white-button',
			'data-xf-click' => 'overlay',
			'href' => $__templater->func('link', array('bh_brands/item/unsub', $__vars['item'], ), false),
		), '', array(
		)) . '
						';
	} else {
		$__finalCompiled .= '
					 ' . $__templater->button('Subsribe', array(
			'id' => 'white-button',
			'href' => $__templater->func('link', array('bh_brands/item/itemsub', $__vars['item'], ), false),
		), '', array(
		)) . '
				';
	}
	$__finalCompiled .= '
	
	';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('bh_brand_hub', 'create_bookmark', )) AND $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '

      	' . $__templater->callMacro(null, 'link', array(
			'content' => $__vars['item'],
			'confirmUrl' => $__templater->func('link', array('bh_brands/item/bookmark', $__vars['item'], ), false),
		), $__vars) . '
	
	';
	}
	$__finalCompiled .= '
	

</div>
';
	return $__finalCompiled;
}
);