<?php
// FROM HASH: faf14fe92ed29983b4dce9f045f53f71
return array(
'macros' => array('attachment_film_strip' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mainItem' => '!',
		'filmStripParams' => '!',
		'item' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
	';
	if (!$__templater->test($__vars['filmStripParams']['Items'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__templater->includeJs(array(
			'src' => 'BrandHub/film_strip.js',
			'min' => '1',
		));
		$__finalCompiled .= '
		';
		$__templater->includeCss('bh_item_attachment_list.less');
		$__finalCompiled .= '

		<div class="block-outer">
			<div class="block-outer-middle">
				<div class="itemList itemList--strip js-filmStrip">
					
					<a data-xf-click="inserter" data-replace=".js-filmStrip"
						href="' . $__templater->func('link', array('bh_brands/item/filmstripjump', $__vars['item'], array('direction' => 'prev', 'attachment_id' => $__vars['filmStripParams']['firstItem']['attachment_id'], ), ), true) . '"
						class="js-filmStrip-button itemList-button itemList-button--prev' . ((!$__vars['filmStripParams']['hasPrev']) ? ' is-disabled' : '') . '">

						<i class="itemList-button-icon"></i>
					</a>

					';
		if ($__templater->isTraversable($__vars['filmStripParams']['Items'])) {
			foreach ($__vars['filmStripParams']['Items'] AS $__vars['attachItem']) {
				$__finalCompiled .= '
						<div class="js-filmStrip-item itemList-item">
							<a href="' . $__templater->func('link', array('bh_brands/item', $__vars['item'], array('attachment_id' => $__vars['attachItem']['attachment_id'], ), ), true) . '">
								
	
					<span class="xfmgThumbnail xfmgThumbnail--image xfmgThumbnail--fluid xfmgThumbnail--iconSmallest ' . (($__vars['mainItem']['attachment_id'] == $__vars['attachItem']['attachment_id']) ? 'is-selected' : '') . ' ">
			        <img class="xfmgThumbnail-image" src="' . $__templater->escape($__templater->method($__vars['attachItem'], 'getThumbnailUrl', array())) . '" >
			           <span class="xfmgThumbnail-icon"></span>
		           </span>
							</a>
						</div>
					';
			}
		}
		$__finalCompiled .= '

					<a data-xf-click="inserter" data-replace=".js-filmStrip"
						href="' . $__templater->func('link', array('bh_brands/item/filmstripjump', $__vars['item'], array('direction' => 'next', 'attachment_id' => $__vars['filmStripParams']['lastItem']['attachment_id'], ), ), true) . '"
						class="js-filmStrip-button itemList-button itemList-button--next' . ((!$__vars['filmStripParams']['hasNext']) ? ' is-disabled' : '') . '">

						<i class="itemList-button-icon"></i>
					</a>
				</div>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'main_content' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mainItem' => '!',
		'item' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('xfrb_media_view.less');
	$__finalCompiled .= '
	';
	if ($__vars['mainItem']) {
		$__finalCompiled .= '
	
			<div class="media-container-image js-mediaContainerImage">
			
			';
		$__vars['imageHtml'] = $__templater->preEscaped('
				<img src="' . $__templater->func('link', array('attachments/full', $__vars['mainItem'], ), true) . '" alt="' . $__templater->escape($__vars['mainItem']['filename']) . '" class="js-mediaImage" />
			');
		$__finalCompiled .= '
		

				' . $__templater->filter($__vars['imageHtml'], array(array('raw', array()),), true) . '
				
		
	</div>
		
	';
	} else if ($__vars['mediaItem']['media_type'] == 'video') {
		$__finalCompiled .= '
		' . $__templater->callMacro('videojs_macros', 'setup', array(), $__vars) . '
   
		<div class="bbMediaWrapper">
			<div class="bbMediaWrapper-inner">
				<video data-xf-init="video-player"
					data-player-setup="' . $__templater->filter($__vars['mediaItem']['player_setup'], array(array('json', array()),), true) . '"
					class="video-js vjs-default-skin vjs-big-play-centered vjs-16-9">

					<source src="' . $__templater->escape($__templater->method($__vars['mediaItem'], 'getVideoUrl', array())) . '" type="video/mp4" />
				</video>
			</div>
		</div>
	';
	} else if ($__vars['mediaItem']['media_type'] == 'audio') {
		$__finalCompiled .= '
		' . $__templater->callMacro('videojs_macros', 'setup', array(), $__vars) . '

		';
		$__vars['audioItem'] = $__templater->preEscaped('
			<audio data-xf-init="video-player"
				data-player-setup="' . $__templater->filter($__vars['mediaItem']['player_setup'], array(array('json', array()),), true) . '"
				class="video-js vjs-default-skin vjs-big-play-centered bbMediaWrapper-fallback">

				<source src="' . $__templater->escape($__templater->method($__vars['mediaItem'], 'getAudioUrl', array())) . '" type="audio/mpeg" />
			</audio>
		');
		$__finalCompiled .= '

		';
		if ($__vars['mediaItem']['thumbnail_date']) {
			$__finalCompiled .= '
			<div class="bbMediaWrapper">
				<div class="bbMediaWrapper-inner bbMediaWrapper-inner--thumbnail">
					' . $__templater->filter($__vars['audioItem'], array(array('raw', array()),), true) . '
				</div>
			</div>
		';
		} else {
			$__finalCompiled .= '
			' . $__templater->filter($__vars['audioItem'], array(array('raw', array()),), true) . '
		';
		}
		$__finalCompiled .= '
	';
	} else if ($__vars['mediaItem']['media_type'] == 'embed') {
		$__finalCompiled .= '
		' . $__templater->func('bb_code', array($__vars['mediaItem']['media_tag'], 'xfmg_media', $__vars['mediaItem'], ), true) . '
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

';
	return $__finalCompiled;
}
);