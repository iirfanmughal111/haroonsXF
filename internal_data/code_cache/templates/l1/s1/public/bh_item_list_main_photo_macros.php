<?php
// FROM HASH: 45808087c22be388191f060ba7b1b013
return array(
'macros' => array('item_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'Items' => '!',
		'Selected' => '',
		'allowInlineMod' => false,
		'forceInlineMod' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
 	
	<div class="itemList">
	
		';
	if ($__templater->isTraversable($__vars['Items'])) {
		foreach ($__vars['Items'] AS $__vars['item']) {
			$__finalCompiled .= '
		
			' . $__templater->callMacro(null, 'media_list_item', array(
				'Item' => $__vars['item'],
				'Selected' => $__vars['Selected'],
				'allowInlineMod' => $__vars['allowInlineMod'],
				'forceInlineMod' => $__vars['forceInlineMod'],
			), $__vars) . '
		';
		}
	}
	$__finalCompiled .= '

	</div>
';
	return $__finalCompiled;
}
),
'media_list_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'Item' => '',
		'Selected' => '',
		'allowInlineMod' => false,
		'forceInlineMod' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
	
	
	
	<div class="itemList-item js-inlineModContainer" >

		
		
			' . $__templater->callMacro(null, 'media_list_item_thumb', array(
		'Item' => $__vars['Item'],
		'Selected' => $__vars['Selected'],
	), $__vars) . '
		
		
	</div>
';
	return $__finalCompiled;
}
),
'media_list_item_thumb' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'Item' => '',
		'Selected' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	

    ';
	if ($__vars['Item']) {
		$__finalCompiled .= '
	   <div style="width:25px;height:25px;background:grey;border-radius:3px;position:relative;top:30px;left:7px;">
     
		';
		if ($__vars['Item']['attachment_id'] == $__vars['Selected']['attachment_id']) {
			$__finalCompiled .= '
		<input type="radio" value ="' . $__templater->escape($__vars['Item']['attachment_id']) . '" name="mainitem"  style="margin:5px;" checked/>
		';
		} else {
			$__finalCompiled .= '
		<input type="radio" value ="' . $__templater->escape($__vars['Item']['attachment_id']) . '" name="mainitem"  style="margin:5px;"/>	
		';
		}
		$__finalCompiled .= '
	</div>
		<span class=" xfmgThumbnail--fluid">
		 <img  src="' . $__templater->escape($__templater->method($__vars['Item'], 'getThumbnailUrl', array())) . '"  width="200px" height="200px">
			      
	   </span>
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
	$__finalCompiled .= '



' . '





';
	return $__finalCompiled;
}
);