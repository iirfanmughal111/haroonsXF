<?php
// FROM HASH: bccd2c239b1ed2f8d35b9191197daadb
return array(
'macros' => array('tv' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'post' => '!',
		'thread' => '!',
		'forum' => null,
		'crews' => array(),
		'crewsTotal' => '0',
		'crewsHasMore' => false,
		'casts' => array(),
		'castsTotal' => '0',
		'castsHasMore' => false,
		'videos' => array(),
		'videosTotal' => '0',
		'videosHasMore' => false,
		'companies' => array(),
		'networks' => array(),
	); },
'extensions' => array('before' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	
	return $__finalCompiled;
},
'watch_providers' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
											';
	$__vars['watchProviders'] = $__templater->method($__vars['thread']['TV'], 'getWatchProviders', array());
	$__finalCompiled .= '
											';
	if ($__vars['watchProviders']) {
		$__finalCompiled .= '
												<div class="message message--tv tv-providers">
													<h4 class="block-textHeader">
														' . 'Where to watch' . '
														<div class="u-pullRight">
															';
		$__compilerTemp1 = array();
		$__compilerTemp2 = $__templater->method($__vars['thread']['TV'], 'getWatchProviderCountries', array());
		if ($__templater->isTraversable($__compilerTemp2)) {
			foreach ($__compilerTemp2 AS $__vars['region'] => $__vars['phrase']) {
				$__compilerTemp1[] = array(
					'value' => $__vars['region'],
					'data-xf-init' => 'disabler',
					'data-container' => '.js-tvWatchData-' . $__vars['region'],
					'data-hide' => 'on',
					'label' => '
																		' . $__templater->escape($__vars['phrase']) . '
																	',
					'_type' => 'option',
				);
			}
		}
		$__finalCompiled .= $__templater->formSelect(array(
			'value' => ($__vars['xf']['visitor']['Option']['snog_tv_tmdb_watch_region'] ?: $__vars['xf']['options']['TvThreads_watchProviderRegion']),
		), $__compilerTemp1) . '
														</div>
													</h4>

													';
		if ($__templater->isTraversable($__vars['watchProviders'])) {
			foreach ($__vars['watchProviders'] AS $__vars['region'] => $__vars['watchProvider']) {
				if ($__vars['watchProvider']['link']) {
					$__finalCompiled .= '

														<div class="js-tvWatchData-' . $__templater->escape($__vars['region']) . '">
															';
					if ($__vars['watchProvider']['flatrate']) {
						$__finalCompiled .= '
																' . 'Streaming' . $__vars['xf']['language']['label_separator'] . '
																' . $__templater->callMacro(null, 'watch_provider_list', array(
							'providerType' => $__vars['watchProvider']['flatrate'],
						), $__vars) . '
															';
					}
					$__finalCompiled .= '

															';
					if ($__vars['watchProvider']['buy']) {
						$__finalCompiled .= '
																' . 'Buy' . $__vars['xf']['language']['label_separator'] . '
																' . $__templater->callMacro(null, 'watch_provider_list', array(
							'providerType' => $__vars['watchProvider']['buy'],
						), $__vars) . '
															';
					}
					$__finalCompiled .= '

															';
					if ($__vars['watchProvider']['rent']) {
						$__finalCompiled .= '
																' . 'Rent' . $__vars['xf']['language']['label_separator'] . '
																' . $__templater->callMacro(null, 'watch_provider_list', array(
							'providerType' => $__vars['watchProvider']['rent'],
						), $__vars) . '
															';
					}
					$__finalCompiled .= '

															<br />
															' . $__templater->button('More details on TMDb', array(
						'href' => $__vars['watchProvider']['link'],
						'target' => '_blank',
					), '', array(
					)) . '
														</div>
													';
				}
			}
		}
		$__finalCompiled .= '
												</div>

											';
	}
	$__finalCompiled .= '
										';
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeCss('message.less');
	$__finalCompiled .= '

	' . $__templater->renderExtension('before', $__vars, $__extensions) . '

	<article class="message message--tv js-post js-inlineModContainer
		' . (($__vars['thread']['discussion_state'] == 'moderated') ? 'is-moderated' : '') . '
		' . (($__vars['thread']['discussion_state'] == 'deleted') ? 'is-deleted' : '') . '"
		data-author="' . ($__templater->escape($__vars['post']['User']['username']) ?: $__templater->escape($__vars['post']['username'])) . '"
		data-content="post-' . $__templater->escape($__vars['post']['post_id']) . '"
		id="js-post-' . $__templater->escape($__vars['post']['post_id']) . '">

		<span class="u-anchorTarget" id="post-' . $__templater->escape($__vars['post']['post_id']) . '"></span>
		<div class="message-inner">
			<div class="message-cell message-cell--main">
				<div class="message-expandWrapper js-expandWatch is-expanded">
					<div class="message-expandContent js-expandContent">
						<div class="message-main js-quickEditTarget">

							' . $__templater->callMacro(null, 'post_macros::post_attribution', array(
		'post' => $__vars['post'],
		'thread' => $__vars['thread'],
		'mainClass' => 'listInline--bullet',
		'showPosition' => false,
	), $__vars) . '

							' . $__templater->callMacro(null, 'post_macros::post_notices', array(
		'post' => $__vars['post'],
		'thread' => $__vars['thread'],
	), $__vars) . '

							<div class="message-content js-messageContent">
								' . '
								<div class="message-tv' . (($__templater->func('property', array('snog_tv_messagePosterPosition', ), false) == 'right') ? ' message-tv--reverse' : '') . '">
									<div class="message-tv-sidebar">
										
										<div class="message-tv-sidebar-poster">
											';
	if ($__vars['post']['TVPost']) {
		$__finalCompiled .= '
												<img src="' . $__templater->escape($__vars['post']['TVPost']['episode_image_url']) . '" />
											';
	} else {
		$__finalCompiled .= '
												<img src="' . $__templater->escape($__templater->method($__vars['thread']['TV'], 'getImageUrl', array('l', 1, ))) . '" />
											';
	}
	$__finalCompiled .= '
											
											';
	if ($__vars['xf']['options']['TvThreads_use_rating']) {
		$__finalCompiled .= '
												' . $__templater->callMacro(null, 'rating_block', array(
			'thread' => $__vars['thread'],
		), $__vars) . '
											';
	}
	$__finalCompiled .= '
										</div>
										
										';
	if (($__templater->func('property', array('snog_tv_messageCompaniesPosition', ), false) == 'left')) {
		$__finalCompiled .= '
											';
		if (!$__templater->test($__vars['companies'], 'empty', array())) {
			$__finalCompiled .= '
												<div class="messageTV tv-companies">
													';
			if ($__templater->func('property', array('snog_tv_messageCompaniesTitle', ), false)) {
				$__finalCompiled .= '
														<h4 class="block-textHeader">' . 'Production companies' . $__vars['xf']['language']['label_separator'] . '</h4>
													';
			}
			$__finalCompiled .= '
													' . $__templater->callMacro(null, 'production_company_list', array(
				'companies' => $__vars['companies'],
			), $__vars) . '
												</div>
											';
		}
		$__finalCompiled .= '
										';
	}
	$__finalCompiled .= '

										';
	if (($__templater->func('property', array('snog_tv_messageNetworksPosition', ), false) == 'left')) {
		$__finalCompiled .= '
											';
		if (!$__templater->test($__vars['networks'], 'empty', array())) {
			$__finalCompiled .= '
												<div class="messageTV tv-networks">
													';
			if ($__templater->func('property', array('snog_tv_messageNetworksTitle', ), false)) {
				$__finalCompiled .= '
														<h4 class="block-textHeader">' . 'Networks' . $__vars['xf']['language']['label_separator'] . '</h4>
													';
			}
			$__finalCompiled .= '
													' . $__templater->callMacro(null, 'network_list', array(
				'networks' => $__vars['networks'],
			), $__vars) . '
												</div>
											';
		}
		$__finalCompiled .= '
										';
	}
	$__finalCompiled .= '
									</div>

									<div class="message-tv-main">
										' . '
										<div class="message message--tv">
											';
	if ($__vars['post']['TVPost']) {
		$__finalCompiled .= '
												';
		if ($__vars['post']['TVPost']['tv_title']) {
			$__finalCompiled .= '
													<b><span itemprop="name">' . $__templater->escape($__vars['post']['TVPost']['tv_title']) . '</span></b><br />
													<b>' . 'Season' . ':</b> <meta itemprop="partOfSeason" content="' . $__templater->escape($__vars['post']['TVPost']['tv_season']) . '">' . $__templater->escape($__vars['post']['TVPost']['tv_season']) . '<br />
													<b>' . 'Episode' . ':</b> <meta itemprop="episodeNumber" content="' . $__templater->escape($__vars['post']['TVPost']['tv_episode']) . '">' . $__templater->escape($__vars['post']['TVPost']['tv_episode']) . '<br />
													<b>' . 'Air date' . ':</b> <meta itemprop="datePublished" content="' . $__templater->escape($__vars['post']['TVPost']['tv_aired']) . '">' . $__templater->escape($__vars['post']['TVPost']['tv_aired']) . '<br /><br />
													';
			if ($__vars['post']['TVPost']['tv_guest']) {
				$__finalCompiled .= '<b>' . 'Guest stars' . ': </b>' . $__templater->escape($__vars['post']['TVPost']['tv_guest']) . '<br />';
			}
			$__finalCompiled .= '
												';
		} else if (!$__templater->test($__vars['post']['Thread'], 'empty', array()) AND $__vars['post']['Thread']['TV']['tv_season']) {
			$__finalCompiled .= '
													<b><span itemprop="name">' . $__templater->escape($__vars['post']['Thread']['TV']['tv_title']) . '</span></b><br />
													<b>' . 'Season' . ':</b> <meta itemprop="partOfSeason" content="' . $__templater->escape($__vars['post']['Thread']['TV']['tv_season']) . '">' . $__templater->escape($__vars['post']['Thread']['TV']['tv_season']) . '<br />
													<b>' . 'Episode' . ':</b> <meta itemprop="episodeNumber" content="' . $__templater->escape($__vars['post']['Thread']['TV']['tv_episode']) . '">' . $__templater->escape($__vars['post']['Thread']['TV']['tv_episode']) . '<br />
													<b>' . 'Air date' . ':</b> <meta itemprop="datePublished" content="' . $__templater->escape($__vars['post']['Thread']['TV']['tv_release']) . '">' . $__templater->escape($__vars['post']['Thread']['TV']['tv_release']) . '<br /><br />
													';
			if ($__vars['post']['Thread']['TV']['tv_cast']) {
				$__finalCompiled .= '<b>' . 'Guest stars' . ': </b>' . $__templater->escape($__vars['post']['Thread']['TV']['tv_cast']) . '<br />';
			}
			$__finalCompiled .= '
												';
		}
		$__finalCompiled .= '
											';
	} else {
		$__finalCompiled .= '
												';
		if ($__vars['xf']['options']['TvThreads_showThread']['title']) {
			$__finalCompiled .= '<b>' . 'Title' . ':</b> ' . $__templater->escape($__vars['thread']['TV']['tv_title']) . '<br />';
		}
		$__finalCompiled .= '
												';
		if ($__vars['xf']['options']['TvThreads_showThread']['genres'] AND $__vars['thread']['TV']['tv_genres']) {
			$__finalCompiled .= '<p><b>' . 'Genre' . ':</b> ' . $__templater->escape($__vars['thread']['TV']['tv_genres']) . '</p>';
		}
		$__finalCompiled .= '
												';
		if ($__vars['xf']['options']['TvThreads_showThread']['director'] AND $__vars['thread']['TV']['tv_director']) {
			$__finalCompiled .= '<p><b>' . 'Director' . ':</b> ' . $__templater->escape($__vars['thread']['TV']['tv_director']) . '</p>';
		}
		$__finalCompiled .= '
												';
		if ($__vars['xf']['options']['TvThreads_showThread']['cast'] AND $__vars['thread']['TV']['tv_cast']) {
			$__finalCompiled .= '<p><b>' . 'Cast' . ':</b> ' . $__templater->escape($__vars['thread']['TV']['tv_cast']) . '</p>';
		}
		$__finalCompiled .= '
												';
		if ($__vars['xf']['options']['TvThreads_showThread']['release'] AND $__vars['thread']['TV']['first_air_date']) {
			$__finalCompiled .= '<p><b>' . 'First aired' . ':</b> ' . $__templater->func('date_dynamic', array($__vars['thread']['TV']['first_air_date'], array(
			))) . '</p>';
		}
		$__finalCompiled .= '
												';
		if ($__vars['xf']['options']['TvThreads_showThread']['last_air_date'] AND $__vars['thread']['TV']['last_air_date']) {
			$__finalCompiled .= '<p><b>' . 'Last air date' . ':</b> ' . $__templater->func('date_dynamic', array($__vars['thread']['TV']['last_air_date'], array(
			))) . '</p>';
		}
		$__finalCompiled .= '
												';
		if ($__vars['xf']['options']['TvThreads_showThread']['status'] AND !$__templater->test($__vars['thread']['TV']['status'], 'empty', array())) {
			$__finalCompiled .= '<p><b>' . 'Show status' . ':</b> ' . $__templater->escape($__vars['thread']['TV']['status']) . '</p>';
		}
		$__finalCompiled .= '
												';
		if ($__vars['xf']['options']['TvThreads_showThread']['plot'] AND $__vars['thread']['TV']['tv_plot']) {
			$__finalCompiled .= '<b>' . 'Overview' . ':</b> ' . $__templater->escape($__vars['thread']['TV']['tv_plot']);
		}
		$__finalCompiled .= '
											';
	}
	$__finalCompiled .= '
										</div>
										' . '
										
										';
	if (($__templater->func('property', array('snog_tv_messageCompaniesPosition', ), false) == 'right')) {
		$__finalCompiled .= '
											';
		if (!$__templater->test($__vars['companies'], 'empty', array())) {
			$__finalCompiled .= '
												<div class="message message--tv tv-companies">
													';
			if ($__templater->func('property', array('snog_tv_messageCompaniesTitle', ), false)) {
				$__finalCompiled .= '
														<h4 class="block-textHeader">' . 'Production companies' . '</h4>
													';
			}
			$__finalCompiled .= '
													' . $__templater->callMacro(null, 'production_company_list', array(
				'companies' => $__vars['companies'],
			), $__vars) . '
												</div>
											';
		}
		$__finalCompiled .= '
										';
	}
	$__finalCompiled .= '

										';
	if (($__templater->func('property', array('snog_tv_messageNetworksPosition', ), false) == 'right')) {
		$__finalCompiled .= '
											';
		if (!$__templater->test($__vars['networks'], 'empty', array())) {
			$__finalCompiled .= '
												<div class="message message--tv tv-networks">
													';
			if ($__templater->func('property', array('snog_tv_messageNetworksTitle', ), false)) {
				$__finalCompiled .= '
														<h4 class="block-textHeader">' . 'Networks' . '</h4>
													';
			}
			$__finalCompiled .= '
													' . $__templater->callMacro(null, 'network_list', array(
				'networks' => $__vars['networks'],
			), $__vars) . '
												</div>
											';
		}
		$__finalCompiled .= '
										';
	}
	$__finalCompiled .= '

										' . $__templater->renderExtension('watch_providers', $__vars, $__extensions) . '
									</div>
								</div>
								' . '

								';
	if (!$__vars['xf']['options']['TvThreads_force_comments']) {
		$__finalCompiled .= '
									<article class="message-body">
										' . $__templater->func('bb_code', array($__vars['thread']['TV']['comment'], 'post', $__vars['post']['User'], array('attachments' => ($__vars['post']['attach_count'] ? $__vars['post']['Attachments'] : array()), 'viewAttachments' => $__templater->method($__vars['thread'], 'canViewAttachments', array()), ), ), true) . '
									</article>
								';
	}
	$__finalCompiled .= '

								';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
												' . '
												';
	if ($__vars['xf']['options']['TvThreads_showThread']['trailer_tab'] AND $__vars['thread']['TV']['tv_trailer']) {
		$__compilerTemp1 .= '
													<a class="tabs-tab is-active" role="tab" tabindex="0" aria-controls="tv-trailer">
														' . 'Trailer' . '
													</a>
												';
	}
	$__compilerTemp1 .= '
												' . '
												';
	if ($__vars['xf']['options']['TvThreads_showThread']['cast_tab'] AND !$__templater->test($__vars['casts'], 'empty', array())) {
		$__compilerTemp1 .= '
													<a class="tabs-tab" role="tab" tabindex="0" aria-controls="tv-cast">
														' . 'Cast' . '
													</a>
												';
	}
	$__compilerTemp1 .= '
												' . '
												';
	if ($__vars['xf']['options']['TvThreads_showThread']['crew_tab'] AND !$__templater->test($__vars['crews'], 'empty', array())) {
		$__compilerTemp1 .= '
													<a class="tabs-tab" role="tab" tabindex="0" aria-controls="tv-crew">
														' . 'Crew' . '
													</a>
												';
	}
	$__compilerTemp1 .= '
												' . '
												';
	if ($__vars['xf']['options']['TvThreads_showThread']['videos_tab'] AND !$__templater->test($__vars['videos'], 'empty', array())) {
		$__compilerTemp1 .= '
													<a class="tabs-tab" role="tab" tabindex="0" aria-controls="tv-crew">
														' . 'Videos' . '
													</a>
												';
	}
	$__compilerTemp1 .= '
												' . '
											';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
									<h2 class="block-tabHeader tabs hScroller" data-xf-init="tabs h-scroller" role="tablist">
										<span class="hScroller-scroll">
											' . $__compilerTemp1 . '
										</span>
									</h2>
								';
	}
	$__finalCompiled .= '
								
								';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
											' . '
											';
	if ($__vars['xf']['options']['TvThreads_showThread']['trailer_tab'] AND $__vars['thread']['TV']['tv_trailer']) {
		$__compilerTemp2 .= '
												<li class="block-body is-active" role="tabpanel" id="tv-trailer">
													<div class="block-row">
														<div class="bbMediaWrapper">
															<div class="bbMediaWrapper-inner">
																<iframe width="500"
																	height="300"
																	allowfullscreen
																	src="https://www.youtube.com/embed/' . $__templater->escape($__vars['thread']['TV']['tv_trailer']) . '?wmode=opaque&start=0"
																	style="border:none;"></iframe>
															</div>
														</div>
													</div>
												</li>
											';
	}
	$__compilerTemp2 .= '
											
											' . '
											';
	if ($__vars['xf']['options']['TvThreads_showThread']['cast_tab'] AND !$__templater->test($__vars['casts'], 'empty', array())) {
		$__compilerTemp2 .= '
												<li class="block-body" role="tabpanel" id="tv-casts">
													<div class="block-row">
														' . $__templater->callMacro('snog_tv_casts_macros', 'cast_list', array(
			'tv' => $__vars['thread']['TV'],
			'casts' => $__vars['casts'],
			'page' => '1',
			'hasMore' => $__vars['castsHasMore'],
		), $__vars) . '
													</div>
												</li>
											';
	}
	$__compilerTemp2 .= '
											
											' . '
											';
	if ($__vars['xf']['options']['TvThreads_showThread']['crew_tab'] AND !$__templater->test($__vars['crews'], 'empty', array())) {
		$__compilerTemp2 .= '
												<li class="block-body" role="tabpanel" id="tv-crew">
													<div class="block-row">
														' . $__templater->callMacro('snog_tv_crews_macros', 'crew_list', array(
			'tv' => $__vars['thread']['TV'],
			'crews' => $__vars['crews'],
			'page' => '1',
			'hasMore' => $__vars['crewsHasMore'],
		), $__vars) . '
													</div>
												</li>
											';
	}
	$__compilerTemp2 .= '
											
											' . '
											';
	if ($__vars['xf']['options']['TvThreads_showThread']['videos_tab'] AND !$__templater->test($__vars['videos'], 'empty', array())) {
		$__compilerTemp2 .= '
												<li class="block-body" role="tabpanel" id="tv-videos">
													<div class="block-row">
														' . $__templater->callMacro('snog_tv_videos_macros', 'video_list', array(
			'tv' => $__vars['thread']['TV'],
			'videos' => $__vars['videos'],
			'page' => '1',
			'hasMore' => $__vars['videosHasMore'],
		), $__vars) . '
													</div>
												</li>
											';
	}
	$__compilerTemp2 .= '
											' . '
										';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
									<ul class="tabPanes">
										' . $__compilerTemp2 . '
									</ul>
								';
	}
	$__finalCompiled .= '

								' . $__templater->callMacro(null, 'post_macros::post_last_edit', array(
		'post' => $__vars['post'],
	), $__vars) . '
							</div>

							' . $__templater->callMacro(null, 'post_macros::post_footer', array(
		'post' => $__vars['post'],
		'thread' => $__vars['thread'],
	), $__vars) . '
						</div>
					</div>
					<div class="message-expandLink js-expandLink"><a role="button" tabindex="0">' . 'Click to expand...' . '</a></div>
				</div>
			</div>
		</div>

		<aside class="message-tvUserInfo">
			<div class="message-cell">
				' . $__templater->callMacro(null, 'author_info', array(
		'user' => $__vars['post']['User'],
		'fallbackName' => $__vars['post']['username'],
	), $__vars) . '
			</div>
		</aside>

	</article>

	' . $__templater->callAdsMacro('post_below_container', array(
		'post' => $__vars['post'],
	), $__vars) . '

';
	return $__finalCompiled;
}
),
'network_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'networks' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<ul class="listHeap">
		';
	if ($__templater->isTraversable($__vars['networks'])) {
		foreach ($__vars['networks'] AS $__vars['network']) {
			$__finalCompiled .= '
			<li>
				<div class="listHeap-iconContainer">
					<a href="' . $__templater->escape($__vars['network']['homepage']) . '" target="_blank" class="tv-network" data-xf-init="tooltip" title="' . $__templater->escape($__vars['network']['name']) . '">
						';
			if (!$__templater->test($__vars['network']['image_url'], 'empty', array())) {
				$__finalCompiled .= '
							<img src="' . $__templater->escape($__vars['network']['image_url']) . '" alt="' . $__templater->escape($__vars['network']['name']) . '"> 
						';
			} else {
				$__finalCompiled .= '
							' . $__templater->escape($__vars['network']['name']) . '
						';
			}
			$__finalCompiled .= '
					</a>
				</div>
			</li>
		';
		}
	}
	$__finalCompiled .= '
	</ul>
';
	return $__finalCompiled;
}
),
'production_company_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'companies' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<ul class="listHeap">
		';
	if ($__templater->isTraversable($__vars['companies'])) {
		foreach ($__vars['companies'] AS $__vars['company']) {
			$__finalCompiled .= '
			<li>
				<div class="listHeap-iconContainer">
					<a href="' . $__templater->escape($__vars['company']['homepage']) . '" target="_blank" class="tv-company" data-xf-init="tooltip" title="' . $__templater->escape($__vars['company']['name']) . '">
						';
			if (!$__templater->test($__vars['company']['image_url'], 'empty', array())) {
				$__finalCompiled .= '
							<img src="' . $__templater->escape($__vars['company']['image_url']) . '" alt="' . $__templater->escape($__vars['company']['name']) . '"> 
						';
			} else {
				$__finalCompiled .= '
							' . $__templater->escape($__vars['company']['name']) . '
						';
			}
			$__finalCompiled .= '
					</a>
				</div>
			</li>
		';
		}
	}
	$__finalCompiled .= '
	</ul>
';
	return $__finalCompiled;
}
),
'watch_provider_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'providerType' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<ul class="listHeap">
		';
	if ($__templater->isTraversable($__vars['providerType'])) {
		foreach ($__vars['providerType'] AS $__vars['watchProvider']) {
			$__finalCompiled .= '
			<li>
				<div class="listHeap-iconContainer">
					<a class="tv-provider" data-xf-init="tooltip" title="' . $__templater->escape($__vars['watchProvider']['provider_name']) . '">
						<img src="https://www.themoviedb.org/t/p/original/' . $__templater->escape($__vars['watchProvider']['logo_path']) . '" width="' . $__templater->func('property', array('snog_tv_watchProviderImgSize', ), true) . '" height="' . $__templater->func('property', array('snog_tv_watchProviderImgSize', ), true) . '"> 
					</a>
				</div>
			</li>
		';
		}
	}
	$__finalCompiled .= '
	</ul>
';
	return $__finalCompiled;
}
),
'author_info' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'user' => '!',
		'fallbackName' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="contentRow">
		<div class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['user'], 'm', false, array(
		'defaultname' => $__vars['fallbackName'],
	))) . '
		</div>
		<div class="contentRow-main">

			<div class="message-articleUserFirstLine">
				<div class="message-articleWrittenBy u-srOnly">' . 'Written by' . '</div>
				<h3 class="message-articleUserName">
					' . $__templater->func('username_link', array($__vars['user'], true, array(
		'defaultname' => $__vars['fallbackName'],
	))) . '
				</h3>

				';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
							' . $__templater->func('user_blurb', array($__vars['user'], array(
		'tag' => 'div',
	))) . '
						';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
					<div class="message-articleUserBlurb">
						' . $__compilerTemp1 . '
					</div>
				';
	}
	$__finalCompiled .= '
			</div>

			';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
						';
	if ($__vars['user']['Profile']['about'] != '') {
		$__compilerTemp2 .= '
							' . $__templater->func('bb_code', array($__vars['user']['Profile']['about'], 'user:about', $__vars['user'], ), true) . '
						';
	}
	$__compilerTemp2 .= '
					';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
				<div class="message-articleUserAbout">
					' . $__compilerTemp2 . '
				</div>
			';
	}
	$__finalCompiled .= '

			';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= $__templater->func('user_banners', array($__vars['user'], array(
	)));
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__finalCompiled .= '
				<div class="message-articleUserBanners">
					' . $__compilerTemp3 . '
				</div>
			';
	}
	$__finalCompiled .= '

			<div class="message-articleUserStats">
				<ul class="listInline listInline--bullet">
					' . '
					<li><dl class="pairs pairs--inline">
						<dt>' . 'Messages' . '</dt>
						<dd>' . $__templater->filter($__vars['user']['message_count'], array(array('number', array()),), true) . '</dd>
					</dl></li>
					' . '
					<li><dl class="pairs pairs--inline">
						<dt>' . 'Reaction score' . '</dt>
						<dd>' . $__templater->filter($__vars['user']['reaction_score'], array(array('number', array()),), true) . '</dd>
					</dl></li>
					' . '
					' . '
					';
	if ($__vars['xf']['options']['enableTrophies']) {
		$__finalCompiled .= '
						<li><dl class="pairs pairs--inline">
							<dt>' . 'Points' . '</dt>
							<dd>' . $__templater->filter($__vars['user']['trophy_points'], array(array('number', array()),), true) . '</dd>
						</dl></li>
					';
	}
	$__finalCompiled .= '
					' . '
				</ul>
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'rating_block' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'thread' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['rating'] = $__vars['thread']['TV']['tv_rating'];
	$__finalCompiled .= '
	<div style="padding-top: 10px;">
		' . $__templater->callMacro('rating_macros', 'stars_text', array(
		'rating' => $__vars['rating'],
		'text' => ' ',
	), $__vars) . '
	</div>

	<div>' . $__templater->escape($__vars['thread']['TV']['tv_rating']) . '/5 ' . $__templater->escape($__vars['thread']['TV']['tv_votes']) . ' ' . 'Votes' . '</div>

	';
	if ($__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
		<div>
			' . $__templater->button('
				' . 'Add/Change rating' . '
			', array(
			'class' => 'button--link button--wrap',
			'href' => $__templater->func('link', array('tv/rate', $__vars['thread']['TV'], ), false),
			'overlay' => 'true',
		), '', array(
		)) . '
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'post' => array(
'extends' => 'post_macros::post',
'extensions' => array('user_content' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
		';
	if (!$__templater->test($__vars['post']['TVPost'], 'empty', array())) {
		$__finalCompiled .= '

			<div class="message messageEpisode" itemprop="episode" itemscope itemtype="http://schema.org/TVEpisode">
				';
		if (!$__templater->test($__vars['post']['Thread']['TV'], 'empty', array())) {
			$__finalCompiled .= '
					<b><meta itemprop="partOfTVSeries" content="' . $__templater->escape($__vars['thread']['TV']['tv_title']) . '">' . $__templater->escape($__vars['thread']['TV']['tv_title']) . '</b><br />
				';
		}
		$__finalCompiled .= '

				<div class="episodeImage">
					';
		if ($__vars['post']['Thread']['TV']['tv_season'] AND $__vars['post']['Thread']['TV']['tv_image']) {
			$__finalCompiled .= '
						<img src="' . $__templater->escape($__templater->method($__vars['post']['Thread']['TV'], 'getEpisodePosterUrl', array())) . '" />
					';
		} else {
			$__finalCompiled .= '
						<img src="' . $__templater->escape($__templater->method($__vars['post']['TVPost'], 'getEpisodeImageUrl', array())) . '" />
					';
		}
		$__finalCompiled .= '
				</div>

				<div class="episodeInfo">
					';
		if ($__vars['post']['TVPost']['tv_title']) {
			$__finalCompiled .= '
						<b><span itemprop="name">' . $__templater->escape($__vars['post']['TVPost']['tv_title']) . '</span></b><br />
						<b>' . 'Season' . ':</b> <meta itemprop="partOfSeason" content="' . $__templater->escape($__vars['post']['TVPost']['tv_season']) . '">' . $__templater->escape($__vars['post']['TVPost']['tv_season']) . '<br />
						<b>' . 'Episode' . ':</b> <meta itemprop="episodeNumber" content="' . $__templater->escape($__vars['post']['TVPost']['tv_episode']) . '">' . $__templater->escape($__vars['post']['TVPost']['tv_episode']) . '<br />
						<b>' . 'Air date' . ':</b> <meta itemprop="datePublished" content="' . $__templater->escape($__vars['post']['TVPost']['tv_aired']) . '">' . $__templater->escape($__vars['post']['TVPost']['tv_aired']) . '<br /><br />
						';
			if ($__vars['post']['TVPost']['tv_guest']) {
				$__finalCompiled .= '<b>' . 'Guest stars' . ': </b>' . $__templater->escape($__vars['post']['TVPost']['tv_guest']) . '<br />';
			}
			$__finalCompiled .= '
					';
		} else if (!$__templater->test($__vars['post']['Thread'], 'empty', array()) AND $__vars['post']['Thread']['TV']['tv_season']) {
			$__finalCompiled .= '
						<b><span itemprop="name">' . $__templater->escape($__vars['post']['Thread']['TV']['tv_title']) . '</span></b><br />
						<b>' . 'Season' . ':</b> <meta itemprop="partOfSeason" content="' . $__templater->escape($__vars['post']['Thread']['TV']['tv_season']) . '">' . $__templater->escape($__vars['post']['Thread']['TV']['tv_season']) . '<br />
						<b>' . 'Episode' . ':</b> <meta itemprop="episodeNumber" content="' . $__templater->escape($__vars['post']['Thread']['TV']['tv_episode']) . '">' . $__templater->escape($__vars['post']['Thread']['TV']['tv_episode']) . '<br />
						<b>' . 'Air date' . ':</b> <meta itemprop="datePublished" content="' . $__templater->escape($__vars['post']['Thread']['TV']['tv_release']) . '">' . $__templater->escape($__vars['post']['Thread']['TV']['tv_release']) . '<br /><br />
						';
			if ($__vars['post']['Thread']['TV']['tv_cast']) {
				$__finalCompiled .= '<b>' . 'Guest stars' . ': </b>' . $__templater->escape($__vars['post']['Thread']['TV']['tv_cast']) . '<br />';
			}
			$__finalCompiled .= '
					';
		}
		$__finalCompiled .= '
				</div>

				<div class="episodePlot">
					';
		if ($__vars['post']['TVPost']['tv_plot']) {
			$__finalCompiled .= '
						' . $__templater->escape($__vars['post']['TVPost']['tv_plot']) . '
					';
		} else if ($__vars['post']['Thread']['TV']['tv_season'] AND $__vars['post']['Thread']['TV']['tv_plot']) {
			$__finalCompiled .= '
						' . $__templater->escape($__vars['post']['Thread']['TV']['tv_plot']) . '
					';
		}
		$__finalCompiled .= '
				</div>
			</div><br />

			';
		if ($__vars['post']['TVPost']['message']) {
			$__finalCompiled .= '
				' . $__templater->func('bb_code', array($__vars['post']['TVPost']['message'], 'post', $__vars['post']['User'], array('attachments' => ($__vars['post']['attach_count'] ? $__vars['post']['Attachments'] : array()), 'viewAttachments' => $__templater->method($__vars['thread'], 'canViewAttachments', array()), ), ), true) . '
			';
		}
		$__finalCompiled .= '
		';
	} else {
		$__finalCompiled .= '
			' . $__templater->renderExtensionParent($__vars, null, $__extensions) . '
		';
	}
	$__finalCompiled .= '
	';
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->renderExtension('user_content', $__vars, $__extensions) . '

';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '

' . '

' . '

' . '

';
	return $__finalCompiled;
}
);