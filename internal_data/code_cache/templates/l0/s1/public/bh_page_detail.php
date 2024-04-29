<?php
// FROM HASH: 4de9511cf59e22afe68a1846dca077eb
return array(
'extensions' => array('footer' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
				' . $__templater->callMacro(null, 'page_reaction_footer', array(
		'page' => $__vars['page'],
	), $__vars) . '
			';
	return $__finalCompiled;
}),
'macros' => array('page_reaction_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'page' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<footer class="message-footer">
		';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
					' . $__templater->callMacro(null, 'page_action_bar', array(
		'page' => $__vars['page'],
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
			<div class="message-actionBar actionBar">
				' . $__compilerTemp1 . '
			</div>
		';
	}
	$__finalCompiled .= '

		<div class="reactionsBar js-reactionsList ' . ($__vars['page']['reactions'] ? 'is-active' : '') . '">
					' . $__templater->func('reactions', array($__vars['page'], 'bh_item/ownerpage/reactions', array())) . '
		</div>

		<div class="js-historyTarget message-historyTarget toggleTarget" data-href="trigger-href"></div>
	</footer>
';
	return $__finalCompiled;
}
),
'page_action_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'page' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
			' . $__templater->func('react', array(array(
		'content' => $__vars['page'],
		'link' => 'bh_item/ownerpage/react',
		'list' => '.js-reactionsList',
	))) . '
		';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="actionBar-set actionBar-set--external">
		' . $__compilerTemp1 . '
		</div>
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['page']['User']['username']) . ' in ' . $__templater->escape($__vars['item']['item_title']) . '  ' . $__templater->escape($__vars['item']['Brand']['brand_title']));
	$__finalCompiled .= '<br>
';
	$__templater->breadcrumbs($__templater->method($__vars['page'], 'getBreadcrumbs', array(false, )));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Main Photo', array(
		'href' => $__templater->func('link', array('bh_item/ownerpage/mainphoto', $__vars['page'], ), false),
		'class' => '',
		'overlay' => 'true',
	), '', array(
	)) . '

');
	$__finalCompiled .= '


';
	$__templater->includeCss('bh_brandHub_list.less');
	$__finalCompiled .= '
		

';
	if ($__vars['filmStripParams']) {
		$__finalCompiled .= '
<div class="media">
	
	';
		if ($__vars['filmStripParams']['prevItem']) {
			$__finalCompiled .= '
		
	<a href="' . $__templater->func('link', array('bh_item/ownerpage/page', $__vars['page'], array('attachment_id' => $__vars['filmStripParams']['prevItem']['attachment_id'], ), ), true) . '" class="media-button media-button--prev" data-xf-key="ArrowLeft"><i class="media-button-icon"></i></a>	
	
		';
		}
		$__finalCompiled .= '

		 <div class="media-container">

     	';
		if ($__vars['mainItem']) {
			$__finalCompiled .= '
        ' . $__templater->callMacro('bh_page_filmstrip_view_macros', 'main_content', array(
				'mainItem' => $__vars['mainItem'],
				'item' => $__vars['page'],
			), $__vars) . '
		 ';
		}
		$__finalCompiled .= '
		</div>
	
	

	';
		if ($__vars['filmStripParams']['nextItem']) {
			$__finalCompiled .= '
		<a href="' . $__templater->func('link', array('bh_item/ownerpage/page', $__vars['page'], array('attachment_id' => $__vars['filmStripParams']['nextItem']['attachment_id'], ), ), true) . '" class="media-button media-button--next" data-xf-key="ArrowRight"><i class="media-button-icon"></i></a>
	
	';
		}
		$__finalCompiled .= '
		
</div>

	
 <div class="block js-mediaInfoBlock">
	
	';
		if ($__vars['filmStripParams']['Items']) {
			$__finalCompiled .= '
	' . $__templater->callMacro('bh_page_filmstrip_view_macros', 'attachment_film_strip', array(
				'mainItem' => $__vars['mainItem'],
				'filmStripParams' => $__vars['filmStripParams'],
				'item' => $__vars['page'],
			), $__vars) . '
	 ';
		}
		$__finalCompiled .= '
</div>
		';
	}
	$__finalCompiled .= '


		
	
<div class=\'clearfix\'></div>
<div class="block-container" style="border: 2px solid orange;">
	<blockquote class="message-body">
	
     <h1>' . 'About My ' . $__templater->escape($__vars['item']['Brand']['brand_title']) . ' ' . $__templater->escape($__vars['item']['item_title']) . '' . '</h1>

		
		' . $__templater->func('bb_code', array($__vars['page']['Detail']['about'], 'about', $__vars['page']['Detail'], ), true) . '<br>
		
		';
	if ($__vars['page']['Detail']['attachment']) {
		$__finalCompiled .= '
			<b>' . 'Attachment' . '</b><br><br>
			' . $__templater->func('bb_code', array($__vars['page']['Detail']['attachment'], 'attachment', $__vars['page']['Detail'], ), true) . '<br>
		';
	}
	$__finalCompiled .= '
		
		';
	if ($__vars['page']['Detail']['customizations']) {
		$__finalCompiled .= '
			<b>' . 'Customization' . '</b><br><br>
		   ' . $__templater->func('bb_code', array($__vars['page']['Detail']['customizations'], 'customizations', $__vars['page']['Detail'], ), true) . '
		 ';
	}
	$__finalCompiled .= '
		';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('bh_brand_hub', 'bh_can_edit_ownerpage', )) AND ($__vars['xf']['visitor']['user_id'] == $__vars['page']['user_id'])) {
		$__finalCompiled .= '
			<a href="' . $__templater->func('link', array('bh_item/ownerpage/edit', $__vars['page'], ), true) . '" >' . 'Edit' . '</a>
		';
	}
	$__finalCompiled .= '
		';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('bh_brand_hub', 'react_page', )) AND $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
			' . $__templater->renderExtension('footer', $__vars, $__extensions) . '
		';
	}
	$__finalCompiled .= '
	</blockquote>
</div>




<div class=\'clearfix\'></div>
<div class="block">
				<div class="block-container">
					<div class="block-header">
						<h3 class="block-minorHeader">' . 'My ' . $__templater->escape($__vars['item']['Brand']['brand_title']) . ' ' . $__templater->escape($__vars['item']['item_title']) . ' Topics' . ' (' . $__templater->escape($__vars['page']['discussion_count']) . ')</h3>
						<div class="p-description">' . 'Here are the most recent ' . $__templater->escape($__vars['item']['item_title']) . ' topics from our community.' . '</div>
					</div>
							<div class="block-body block-row block-row--separated">
								<div class="block-body">
									';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['discussions'], 'empty', array())) {
		$__compilerTemp1 .= '
											';
		if ($__templater->isTraversable($__vars['discussions'])) {
			foreach ($__vars['discussions'] AS $__vars['discussion']) {
				$__compilerTemp1 .= '
											';
				if ($__templater->method($__vars['discussion'], 'isUnread', array())) {
					$__compilerTemp1 .= '
												' . $__templater->dataRow(array(
					), array(array(
						'style' => 'font-weight:700;',
						'href' => $__templater->func('link', array('threads', $__vars['discussion'], ), false),
						'target' => '_blank',
						'_type' => 'cell',
						'html' => '<i class="fal fa-greater-than"></i>&nbsp;&nbsp;' . $__templater->func('prefix', array('thread', $__vars['discussion'], ), true) . $__templater->escape($__vars['discussion']['title']),
					))) . '
												';
				} else {
					$__compilerTemp1 .= '
													' . $__templater->dataRow(array(
					), array(array(
						'href' => $__templater->func('link', array('threads', $__vars['discussion'], ), false),
						'target' => '_blank',
						'_type' => 'cell',
						'html' => '<i class="fal fa-greater-than"></i>&nbsp;&nbsp;' . $__templater->escape($__vars['discussion']['title']),
					))) . '
												';
				}
				$__compilerTemp1 .= '
											';
			}
		}
		$__compilerTemp1 .= '
											';
		if ($__vars['page']['discussion_count'] > $__vars['xf']['options']['bh_discussions_on_item']) {
			$__compilerTemp1 .= '
													' . $__templater->dataRow(array(
			), array(array(
				'href' => $__templater->func('link', array('bh_item/ownerpage/pagethreads', $__vars['page'], ), false),
				'target' => '_blank',
				'_type' => 'cell',
				'html' => '<i class="fal fa-greater-than"></i>&nbsp;&nbsp;' . 'View More Threads',
			))) . '
												';
		}
		$__compilerTemp1 .= '
										';
	} else {
		$__compilerTemp1 .= '
											<div class="blockMessage">' . 'No results found.' . '</div>
										';
	}
	$__finalCompiled .= $__templater->dataList('
										
										' . $__compilerTemp1 . '

									', array(
		'data-xf-init' => 'responsive-data-list',
	)) . '
								</div>
							</div>			
				</div>
			</div>



		';
	$__compilerTemp2 = '';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
											  ';
	$__compilerTemp4 = '';
	if ($__vars['pagePosition']['pageViewPosition']) {
		$__compilerTemp4 .= '
															' . $__templater->dataRow(array(
		), array(array(
			'_type' => 'cell',
			'html' => '#' . $__templater->escape($__vars['pagePosition']['pageViewPosition']) . ' most Viewed',
		))) . '	
													';
	}
	$__compilerTemp5 = '';
	if ($__vars['pagePosition']['pageDiscussionPosition']) {
		$__compilerTemp5 .= '
															' . $__templater->dataRow(array(
		), array(array(
			'_type' => 'cell',
			'html' => '#' . $__templater->escape($__vars['pagePosition']['pageDiscussionPosition']) . ' most discussions',
		))) . '	
													';
	}
	$__compilerTemp6 = '';
	if ($__vars['pagePosition']['pageFollowPosition']) {
		$__compilerTemp6 .= '
															' . $__templater->dataRow(array(
		), array(array(
			'_type' => 'cell',
			'html' => '#' . $__templater->escape($__vars['pagePosition']['pageFollowPosition']) . ' most followed',
		))) . '	
													';
	}
	$__compilerTemp7 = '';
	if ($__vars['pagePosition']['pageAttachmentPosition']) {
		$__compilerTemp7 .= '
															' . $__templater->dataRow(array(
		), array(array(
			'_type' => 'cell',
			'html' => '#' . $__templater->escape($__vars['pagePosition']['pageAttachmentPosition']) . ' most photos',
		))) . '	
													';
	}
	$__compilerTemp3 .= $__templater->dataList('

													' . $__compilerTemp4 . '

												  ' . $__compilerTemp5 . '
												  					' . $__compilerTemp6 . '
												  ' . $__compilerTemp7 . '
											', array(
		'data-xf-init' => 'responsive-data-list',
	)) . '
								';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp2 .= '
         
					<h2 class="block-minorHeader">' . 'My ' . $__templater->escape($__vars['item']['Brand']['brand_title']) . ' ' . $__templater->escape($__vars['item']['item_title']) . ' Rankings' . '</h2>
					
 						<div class="block-body block-row">

									' . $__compilerTemp3 . '


								<br><br>

							</div>
					';
	}
	$__templater->modifySidebarHtml('', '
			<div class="block">
				<div class="block-container">
					' . $__compilerTemp2 . '
				</div>
			</div>
		', 'replace');
	$__finalCompiled .= '

	    ';
	$__compilerTemp8 = '';
	if ($__vars['alreadySub']) {
		$__compilerTemp8 .= '
					  <div class="block">
						<div class="block-container bh_center">
							' . 'You have Followed' . '
						  </div>
					  </div>
					';
	} else {
		$__compilerTemp8 .= '
						' . $__templater->button('Follow The Owner Page', array(
			'href' => $__templater->func('link', array('bh_item/ownerpage/pagesub', $__vars['page'], ), false),
			'class' => 'button--fullWidth',
		), '', array(
		)) . '
					';
	}
	$__templater->modifySidebarHtml(null, '
			
				  ' . $__compilerTemp8 . '
				
		  <br>
		', 'replace');
	$__finalCompiled .= '

' . $__templater->callMacro('bh_ad_macros', 'sideBar_pageside', array(), $__vars) . '

' . '


';
	return $__finalCompiled;
}
);