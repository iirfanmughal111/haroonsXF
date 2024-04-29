<?php

return function($__templater, $__selectedNav, array $__vars)
{
	$__tree = [];
	$__flat = [];


	$__navTemp = [
		'title' => \XF::phrase('nav._default'),
		'href' => '',
		'attributes' => [],
	];
	if ($__navTemp) {
		$__tree['_default'] = $__navTemp;
		$__flat['_default'] =& $__tree['_default'];
		if (empty($__tree['_default']['children'])) { $__tree['_default']['children'] = []; }

		if ($__vars['xf']['visitor']['user_id']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.defaultNewsFeed'),
		'href' => $__templater->func('link', array('whats-new/news-feed', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['_default']['children']['defaultNewsFeed'] = $__navTemp;
				$__flat['defaultNewsFeed'] =& $__tree['_default']['children']['defaultNewsFeed'];
			}
		}

		$__navTemp = [
		'title' => \XF::phrase('nav.defaultLatestActivity'),
		'href' => $__templater->func('link', array('whats-new/latest-activity', ), false),
		'attributes' => [],
	];
		if ($__navTemp) {
			$__tree['_default']['children']['defaultLatestActivity'] = $__navTemp;
			$__flat['defaultLatestActivity'] =& $__tree['_default']['children']['defaultLatestActivity'];
		}

		if ($__vars['xf']['visitor']['user_id']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.defaultYourProfile'),
		'href' => $__templater->func('link', array('members', $__vars['xf']['visitor'], ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['_default']['children']['defaultYourProfile'] = $__navTemp;
				$__flat['defaultYourProfile'] =& $__tree['_default']['children']['defaultYourProfile'];
			}
		}

		if ($__vars['xf']['visitor']['user_id']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.defaultYourAccount'),
		'href' => $__templater->func('link', array('account', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['_default']['children']['defaultYourAccount'] = $__navTemp;
				$__flat['defaultYourAccount'] =& $__tree['_default']['children']['defaultYourAccount'];
			}
		}

		if ($__vars['xf']['visitor']['user_id']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.defaultLogOut'),
		'href' => $__templater->func('link', array('logout', null, array('t' => $__templater->func('csrf_token', array(), false), ), ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['_default']['children']['defaultLogOut'] = $__navTemp;
				$__flat['defaultLogOut'] =& $__tree['_default']['children']['defaultLogOut'];
			}
		}

		if (((!$__vars['xf']['visitor']['user_id']) AND $__vars['xf']['options']['registrationSetup']['enabled'])) {
			$__navTemp = [
		'title' => \XF::phrase('nav.defaultRegister'),
		'href' => $__templater->func('link', array('register', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['_default']['children']['defaultRegister'] = $__navTemp;
				$__flat['defaultRegister'] =& $__tree['_default']['children']['defaultRegister'];
			}
		}

	}

	if ($__vars['xf']['homePageUrl']) {
		$__navTemp = [
		'title' => \XF::phrase('nav.home'),
		'href' => $__vars['xf']['homePageUrl'],
		'attributes' => [],
	];
		if ($__navTemp) {
			$__tree['home'] = $__navTemp;
			$__flat['home'] =& $__tree['home'];
		}
	}

	$__navTemp = [
		'title' => \XF::phrase('nav.forums'),
		'href' => $__templater->func('link', array('forums', ), false),
		'attributes' => [],
	];
	if ($__navTemp) {
		$__tree['forums'] = $__navTemp;
		$__flat['forums'] =& $__tree['forums'];
		if (empty($__tree['forums']['children'])) { $__tree['forums']['children'] = []; }

		if (($__vars['xf']['options']['forumsDefaultPage'] != 'new_posts')) {
			$__navTemp = [
		'title' => \XF::phrase('nav.newPosts'),
		'href' => $__templater->func('link', array('whats-new/posts', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['forums']['children']['newPosts'] = $__navTemp;
				$__flat['newPosts'] =& $__tree['forums']['children']['newPosts'];
			}
		}

		if (($__vars['xf']['options']['forumsDefaultPage'] != 'forums')) {
			$__navTemp = [
		'title' => \XF::phrase('nav.forumList'),
		'href' => $__templater->func('link', array('forums/list', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['forums']['children']['forumList'] = $__navTemp;
				$__flat['forumList'] =& $__tree['forums']['children']['forumList'];
			}
		}

		if ($__vars['xf']['visitor']['user_id']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.findThreads'),
		'href' => $__templater->func('link', array('find-threads/started', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['forums']['children']['findThreads'] = $__navTemp;
				$__flat['findThreads'] =& $__tree['forums']['children']['findThreads'];
				if (empty($__tree['forums']['children']['findThreads']['children'])) { $__tree['forums']['children']['findThreads']['children'] = []; }

				if ($__vars['xf']['visitor']['user_id']) {
					$__navTemp = [
		'title' => \XF::phrase('nav.yourThreads'),
		'href' => $__templater->func('link', array('find-threads/started', ), false),
		'attributes' => [],
	];
					if ($__navTemp) {
						$__tree['forums']['children']['findThreads']['children']['yourThreads'] = $__navTemp;
						$__flat['yourThreads'] =& $__tree['forums']['children']['findThreads']['children']['yourThreads'];
					}
				}

				if ($__vars['xf']['visitor']['user_id']) {
					$__navTemp = [
		'title' => \XF::phrase('nav.contributedThreads'),
		'href' => $__templater->func('link', array('find-threads/contributed', ), false),
		'attributes' => [],
	];
					if ($__navTemp) {
						$__tree['forums']['children']['findThreads']['children']['contributedThreads'] = $__navTemp;
						$__flat['contributedThreads'] =& $__tree['forums']['children']['findThreads']['children']['contributedThreads'];
					}
				}

				$__navTemp = [
		'title' => \XF::phrase('nav.unansweredThreads'),
		'href' => $__templater->func('link', array('find-threads/unanswered', ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['forums']['children']['findThreads']['children']['unansweredThreads'] = $__navTemp;
					$__flat['unansweredThreads'] =& $__tree['forums']['children']['findThreads']['children']['unansweredThreads'];
				}

			}
		}

		if ($__vars['xf']['visitor']['user_id']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.watched'),
		'href' => $__templater->func('link', array('watched/threads', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['forums']['children']['watched'] = $__navTemp;
				$__flat['watched'] =& $__tree['forums']['children']['watched'];
				if (empty($__tree['forums']['children']['watched']['children'])) { $__tree['forums']['children']['watched']['children'] = []; }

				if ($__vars['xf']['visitor']['user_id']) {
					$__navTemp = [
		'title' => \XF::phrase('nav.watchedThreads'),
		'href' => $__templater->func('link', array('watched/threads', ), false),
		'attributes' => [],
	];
					if ($__navTemp) {
						$__tree['forums']['children']['watched']['children']['watchedThreads'] = $__navTemp;
						$__flat['watchedThreads'] =& $__tree['forums']['children']['watched']['children']['watchedThreads'];
					}
				}

				if ($__vars['xf']['visitor']['user_id']) {
					$__navTemp = [
		'title' => \XF::phrase('nav.watchedForums'),
		'href' => $__templater->func('link', array('watched/forums', ), false),
		'attributes' => [],
	];
					if ($__navTemp) {
						$__tree['forums']['children']['watched']['children']['watchedForums'] = $__navTemp;
						$__flat['watchedForums'] =& $__tree['forums']['children']['watched']['children']['watchedForums'];
					}
				}

			}
		}

		if ($__templater->method($__vars['xf']['visitor'], 'canSearch', array())) {
			$__navTemp = [
		'title' => \XF::phrase('nav.searchForums'),
		'href' => $__templater->func('link', array('search', null, array('type' => 'post', ), ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['forums']['children']['searchForums'] = $__navTemp;
				$__flat['searchForums'] =& $__tree['forums']['children']['searchForums'];
			}
		}

		if ($__vars['xf']['visitor']['user_id']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.markForumsRead'),
		'href' => $__templater->func('link', array('forums/mark-read', '-', array('date' => $__vars['xf']['time'], ), ), false),
		'attributes' => [
			'data-xf-click' => 'overlay',
		],
	];
			if ($__navTemp) {
				$__tree['forums']['children']['markForumsRead'] = $__navTemp;
				$__flat['markForumsRead'] =& $__tree['forums']['children']['markForumsRead'];
			}
		}

	}

	$__navTemp = [
		'title' => \XF::phrase('nav.whatsNew'),
		'href' => $__templater->func('link', array('whats-new', ), false),
		'attributes' => [],
	];
	if ($__navTemp) {
		$__tree['whatsNew'] = $__navTemp;
		$__flat['whatsNew'] =& $__tree['whatsNew'];
		if (empty($__tree['whatsNew']['children'])) { $__tree['whatsNew']['children'] = []; }

		$__navTemp = [
		'title' => \XF::phrase('nav.whatsNewPosts'),
		'href' => $__templater->func('link', array('whats-new/posts', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
		if ($__navTemp) {
			$__tree['whatsNew']['children']['whatsNewPosts'] = $__navTemp;
			$__flat['whatsNewPosts'] =& $__tree['whatsNew']['children']['whatsNewPosts'];
		}

		if ($__templater->method($__vars['xf']['visitor'], 'canViewMedia', array())) {
			$__navTemp = [
		'title' => \XF::phrase('nav.xfmgWhatsNewNewMedia'),
		'href' => $__templater->func('link', array('whats-new/media', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['whatsNew']['children']['xfmgWhatsNewNewMedia'] = $__navTemp;
				$__flat['xfmgWhatsNewNewMedia'] =& $__tree['whatsNew']['children']['xfmgWhatsNewNewMedia'];
			}
		}

		if ($__templater->method($__vars['xf']['visitor'], 'canViewMedia', array())) {
			$__navTemp = [
		'title' => \XF::phrase('nav.xfmgWhatsNewMediaComments'),
		'href' => $__templater->func('link', array('whats-new/media-comments', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['whatsNew']['children']['xfmgWhatsNewMediaComments'] = $__navTemp;
				$__flat['xfmgWhatsNewMediaComments'] =& $__tree['whatsNew']['children']['xfmgWhatsNewMediaComments'];
			}
		}

		if ($__templater->method($__vars['xf']['visitor'], 'canViewResources', array())) {
			$__navTemp = [
		'title' => \XF::phrase('nav.xfrmNewResources'),
		'href' => $__templater->func('link', array('whats-new/resources', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['whatsNew']['children']['xfrmNewResources'] = $__navTemp;
				$__flat['xfrmNewResources'] =& $__tree['whatsNew']['children']['xfrmNewResources'];
			}
		}

		if ($__templater->method($__vars['xf']['visitor'], 'canViewProfilePosts', array())) {
			$__navTemp = [
		'title' => \XF::phrase('nav.whatsNewProfilePosts'),
		'href' => $__templater->func('link', array('whats-new/profile-posts', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['whatsNew']['children']['whatsNewProfilePosts'] = $__navTemp;
				$__flat['whatsNewProfilePosts'] =& $__tree['whatsNew']['children']['whatsNewProfilePosts'];
			}
		}

		if (($__vars['xf']['options']['enableNewsFeed'] AND $__vars['xf']['visitor']['user_id'])) {
			$__navTemp = [
		'title' => \XF::phrase('nav.whatsNewNewsFeed'),
		'href' => $__templater->func('link', array('whats-new/news-feed', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['whatsNew']['children']['whatsNewNewsFeed'] = $__navTemp;
				$__flat['whatsNewNewsFeed'] =& $__tree['whatsNew']['children']['whatsNewNewsFeed'];
			}
		}

		if ($__vars['xf']['options']['enableNewsFeed']) {
			$__navTemp = [
		'title' => \XF::phrase('nav.latestActivity'),
		'href' => $__templater->func('link', array('whats-new/latest-activity', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['whatsNew']['children']['latestActivity'] = $__navTemp;
				$__flat['latestActivity'] =& $__tree['whatsNew']['children']['latestActivity'];
			}
		}

	}

	if ($__templater->method($__vars['xf']['visitor'], 'canViewMedia', array())) {
		$__navTemp = [
		'title' => \XF::phrase('nav.xfmg'),
		'href' => $__templater->func('link', array('media', ), false),
		'attributes' => [],
	];
		if ($__navTemp) {
			$__tree['xfmg'] = $__navTemp;
			$__flat['xfmg'] =& $__tree['xfmg'];
			if (empty($__tree['xfmg']['children'])) { $__tree['xfmg']['children'] = []; }

			$__navTemp = [
		'title' => \XF::phrase('nav.xfmgNewMedia'),
		'href' => $__templater->func('link', array('whats-new/media', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['xfmg']['children']['xfmgNewMedia'] = $__navTemp;
				$__flat['xfmgNewMedia'] =& $__tree['xfmg']['children']['xfmgNewMedia'];
			}

			$__navTemp = [
		'title' => \XF::phrase('nav.xfmgNewComments'),
		'href' => $__templater->func('link', array('whats-new/media-comments', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
			if ($__navTemp) {
				$__tree['xfmg']['children']['xfmgNewComments'] = $__navTemp;
				$__flat['xfmgNewComments'] =& $__tree['xfmg']['children']['xfmgNewComments'];
			}

			if ($__templater->method($__vars['xf']['visitor'], 'canAddMedia', array())) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xfmgAddMedia'),
		'href' => $__templater->func('link', array('media/add', ), false),
		'attributes' => [
			'data-xf-click' => 'overlay',
		],
	];
				if ($__navTemp) {
					$__tree['xfmg']['children']['xfmgAddMedia'] = $__navTemp;
					$__flat['xfmgAddMedia'] =& $__tree['xfmg']['children']['xfmgAddMedia'];
				}
			}

			if ($__vars['xf']['visitor']['user_id']) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xfmgYourContent'),
		'href' => $__templater->func('link', array('media/users', $__vars['xf']['visitor'], ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['xfmg']['children']['xfmgYourContent'] = $__navTemp;
					$__flat['xfmgYourContent'] =& $__tree['xfmg']['children']['xfmgYourContent'];
					if (empty($__tree['xfmg']['children']['xfmgYourContent']['children'])) { $__tree['xfmg']['children']['xfmgYourContent']['children'] = []; }

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xfmgYourMedia'),
		'href' => $__templater->func('link', array('media/users', $__vars['xf']['visitor'], ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xfmg']['children']['xfmgYourContent']['children']['xfmgYourMedia'] = $__navTemp;
							$__flat['xfmgYourMedia'] =& $__tree['xfmg']['children']['xfmgYourContent']['children']['xfmgYourMedia'];
						}
					}

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xfmgYourAlbums'),
		'href' => $__templater->func('link', array('media/albums/users', $__vars['xf']['visitor'], ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xfmg']['children']['xfmgYourContent']['children']['xfmgYourAlbums'] = $__navTemp;
							$__flat['xfmgYourAlbums'] =& $__tree['xfmg']['children']['xfmgYourContent']['children']['xfmgYourAlbums'];
						}
					}

				}
			}

			if ($__vars['xf']['visitor']['user_id']) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xfmgWatchedContent'),
		'href' => $__templater->func('link', array('watched/media', ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['xfmg']['children']['xfmgWatchedContent'] = $__navTemp;
					$__flat['xfmgWatchedContent'] =& $__tree['xfmg']['children']['xfmgWatchedContent'];
					if (empty($__tree['xfmg']['children']['xfmgWatchedContent']['children'])) { $__tree['xfmg']['children']['xfmgWatchedContent']['children'] = []; }

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xfmgWatchedMedia'),
		'href' => $__templater->func('link', array('watched/media', ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xfmg']['children']['xfmgWatchedContent']['children']['xfmgWatchedMedia'] = $__navTemp;
							$__flat['xfmgWatchedMedia'] =& $__tree['xfmg']['children']['xfmgWatchedContent']['children']['xfmgWatchedMedia'];
						}
					}

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xfmgWatchedAlbums'),
		'href' => $__templater->func('link', array('watched/media-albums', ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xfmg']['children']['xfmgWatchedContent']['children']['xfmgWatchedAlbums'] = $__navTemp;
							$__flat['xfmgWatchedAlbums'] =& $__tree['xfmg']['children']['xfmgWatchedContent']['children']['xfmgWatchedAlbums'];
						}
					}

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xfmgWatchedCategories'),
		'href' => $__templater->func('link', array('watched/media-categories', ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xfmg']['children']['xfmgWatchedContent']['children']['xfmgWatchedCategories'] = $__navTemp;
							$__flat['xfmgWatchedCategories'] =& $__tree['xfmg']['children']['xfmgWatchedContent']['children']['xfmgWatchedCategories'];
						}
					}

				}
			}

			if ($__templater->method($__vars['xf']['visitor'], 'canSearch', array())) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xfmgSearchMedia'),
		'href' => $__templater->func('link', array('search', null, array('type' => 'xfmg_media', ), ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['xfmg']['children']['xfmgSearchMedia'] = $__navTemp;
					$__flat['xfmgSearchMedia'] =& $__tree['xfmg']['children']['xfmgSearchMedia'];
				}
			}

			if ($__vars['xf']['visitor']['user_id']) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xfmgMarkViewed'),
		'href' => $__templater->func('link', array('media/mark-viewed', null, array('date' => $__vars['xf']['time'], ), ), false),
		'attributes' => [
			'data-xf-click' => 'overlay',
		],
	];
				if ($__navTemp) {
					$__tree['xfmg']['children']['xfmgMarkViewed'] = $__navTemp;
					$__flat['xfmgMarkViewed'] =& $__tree['xfmg']['children']['xfmgMarkViewed'];
				}
			}

		}
	}

	if ($__templater->method($__vars['xf']['visitor'], 'canViewResources', array())) {
		$__navTemp = [
		'title' => \XF::phrase('nav.xfrm'),
		'href' => $__templater->func('link', array('resources', ), false),
		'attributes' => [],
	];
		if ($__navTemp) {
			$__tree['xfrm'] = $__navTemp;
			$__flat['xfrm'] =& $__tree['xfrm'];
			if (empty($__tree['xfrm']['children'])) { $__tree['xfrm']['children'] = []; }

			$__navTemp = [
		'title' => \XF::phrase('nav.xfrmLatestReviews'),
		'href' => $__templater->func('link', array('resources/latest-reviews', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['xfrm']['children']['xfrmLatestReviews'] = $__navTemp;
				$__flat['xfrmLatestReviews'] =& $__tree['xfrm']['children']['xfrmLatestReviews'];
			}

			if ($__vars['xf']['visitor']['user_id']) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xfrmYourResources'),
		'href' => $__templater->func('link', array('resources/authors', $__vars['xf']['visitor'], ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['xfrm']['children']['xfrmYourResources'] = $__navTemp;
					$__flat['xfrmYourResources'] =& $__tree['xfrm']['children']['xfrmYourResources'];
				}
			}

			if ($__vars['xf']['visitor']['user_id']) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xfrmWatched'),
		'href' => $__templater->func('link', array('watched/resources', ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['xfrm']['children']['xfrmWatched'] = $__navTemp;
					$__flat['xfrmWatched'] =& $__tree['xfrm']['children']['xfrmWatched'];
					if (empty($__tree['xfrm']['children']['xfrmWatched']['children'])) { $__tree['xfrm']['children']['xfrmWatched']['children'] = []; }

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xfrmWatchedResources'),
		'href' => $__templater->func('link', array('watched/resources', ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xfrm']['children']['xfrmWatched']['children']['xfrmWatchedResources'] = $__navTemp;
							$__flat['xfrmWatchedResources'] =& $__tree['xfrm']['children']['xfrmWatched']['children']['xfrmWatchedResources'];
						}
					}

					if ($__vars['xf']['visitor']['user_id']) {
						$__navTemp = [
		'title' => \XF::phrase('nav.xfrmWatchedCategories'),
		'href' => $__templater->func('link', array('watched/resource-categories', ), false),
		'attributes' => [],
	];
						if ($__navTemp) {
							$__tree['xfrm']['children']['xfrmWatched']['children']['xfrmWatchedCategories'] = $__navTemp;
							$__flat['xfrmWatchedCategories'] =& $__tree['xfrm']['children']['xfrmWatched']['children']['xfrmWatchedCategories'];
						}
					}

				}
			}

			if ($__templater->method($__vars['xf']['visitor'], 'canSearch', array())) {
				$__navTemp = [
		'title' => \XF::phrase('nav.xfrmSearchResources'),
		'href' => $__templater->func('link', array('search', null, array('type' => 'resource', ), ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['xfrm']['children']['xfrmSearchResources'] = $__navTemp;
					$__flat['xfrmSearchResources'] =& $__tree['xfrm']['children']['xfrmSearchResources'];
				}
			}

		}
	}

	if ($__templater->method($__vars['xf']['visitor'], 'canViewMemberList', array())) {
		$__navTemp = [
		'title' => \XF::phrase('nav.members'),
		'href' => $__templater->func('link', array('members', ), false),
		'attributes' => [],
	];
		if ($__navTemp) {
			$__tree['members'] = $__navTemp;
			$__flat['members'] =& $__tree['members'];
			if (empty($__tree['members']['children'])) { $__tree['members']['children'] = []; }

			if ($__vars['xf']['options']['enableMemberList']) {
				$__navTemp = [
		'title' => \XF::phrase('nav.registeredMembers'),
		'href' => $__templater->func('link', array('members/list', ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['members']['children']['registeredMembers'] = $__navTemp;
					$__flat['registeredMembers'] =& $__tree['members']['children']['registeredMembers'];
				}
			}

			$__navTemp = [
		'title' => \XF::phrase('nav.currentVisitors'),
		'href' => $__templater->func('link', array('online', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['members']['children']['currentVisitors'] = $__navTemp;
				$__flat['currentVisitors'] =& $__tree['members']['children']['currentVisitors'];
			}

			if ($__templater->method($__vars['xf']['visitor'], 'canViewProfilePosts', array())) {
				$__navTemp = [
		'title' => \XF::phrase('nav.newProfilePosts'),
		'href' => $__templater->func('link', array('whats-new/profile-posts', ), false),
		'attributes' => [
			'rel' => 'nofollow',
		],
	];
				if ($__navTemp) {
					$__tree['members']['children']['newProfilePosts'] = $__navTemp;
					$__flat['newProfilePosts'] =& $__tree['members']['children']['newProfilePosts'];
				}
			}

			if (($__templater->method($__vars['xf']['visitor'], 'canSearch', array()) AND $__templater->method($__vars['xf']['visitor'], 'canViewProfilePosts', array()))) {
				$__navTemp = [
		'title' => \XF::phrase('nav.searchProfilePosts'),
		'href' => $__templater->func('link', array('search', null, array('type' => 'profile_post', ), ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['members']['children']['searchProfilePosts'] = $__navTemp;
					$__flat['searchProfilePosts'] =& $__tree['members']['children']['searchProfilePosts'];
				}
			}

		}
	}

	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('tl_groups', 'view', ))) {
		$__navTemp = [
		'title' => \XF::phrase('nav.tl_groups'),
		'href' => $__templater->func('link', array('groups', ), false),
		'attributes' => [],
	];
		if ($__navTemp) {
			$__tree['tl_groups'] = $__navTemp;
			$__flat['tl_groups'] =& $__tree['tl_groups'];
			if (empty($__tree['tl_groups']['children'])) { $__tree['tl_groups']['children'] = []; }

			$__navTemp = [
		'title' => \XF::phrase('nav.tl_groups_search'),
		'href' => $__templater->func('link', array('search', null, array('type' => 'tl_group', ), ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['tl_groups']['children']['tl_groups_search'] = $__navTemp;
				$__flat['tl_groups_search'] =& $__tree['tl_groups']['children']['tl_groups_search'];
			}

			if (($__vars['xf']['visitor']['user_id'] > 0)) {
				$__navTemp = [
		'title' => \XF::phrase('nav.tl_groups_joined'),
		'href' => $__templater->func('link', array('groups/browse/joined', ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['tl_groups']['children']['tl_groups_joined'] = $__navTemp;
					$__flat['tl_groups_joined'] =& $__tree['tl_groups']['children']['tl_groups_joined'];
				}
			}

			$__navTemp = [
		'title' => \XF::phrase('nav.tl_groups_upcomingEvents'),
		'href' => $__templater->func('link', array('groups/browse/events', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['tl_groups']['children']['tl_groups_upcomingEvents'] = $__navTemp;
				$__flat['tl_groups_upcomingEvents'] =& $__tree['tl_groups']['children']['tl_groups_upcomingEvents'];
			}

			if (($__vars['xf']['visitor']['user_id'] > 0)) {
				$__navTemp = [
		'title' => \XF::phrase('nav.tlg_feeds'),
		'href' => $__templater->func('link', array('groups/browse/feeds', ), false),
		'attributes' => [],
	];
				if ($__navTemp) {
					$__tree['tl_groups']['children']['tlg_feeds'] = $__navTemp;
					$__flat['tlg_feeds'] =& $__tree['tl_groups']['children']['tlg_feeds'];
				}
			}

		}
	}

	$__navTemp = [
		'title' => \XF::phrase('nav.createCrud'),
		'href' => $__templater->func('link', array('crud', ), false),
		'attributes' => [],
	];
	if ($__navTemp) {
		$__tree['createCrud'] = $__navTemp;
		$__flat['createCrud'] =& $__tree['createCrud'];
		if (empty($__tree['createCrud']['children'])) { $__tree['createCrud']['children'] = []; }

		$__navTemp = [
		'title' => \XF::phrase('nav.addRecord'),
		'href' => $__templater->func('link', array('crud/add', ), false),
		'attributes' => [],
	];
		if ($__navTemp) {
			$__tree['createCrud']['children']['addRecord'] = $__navTemp;
			$__flat['addRecord'] =& $__tree['createCrud']['children']['addRecord'];
		}

	}

	if ($__templater->method($__vars['xf']['visitor'], 'canViewDbtechCredits', array())) {
		$__navTemp = [
		'title' => \XF::phrase('nav.dbtechCredits'),
		'href' => $__templater->func('link', array('dbtech-credits', ), false),
		'attributes' => [],
	];
		if ($__navTemp) {
			$__tree['dbtechCredits'] = $__navTemp;
			$__flat['dbtechCredits'] =& $__tree['dbtechCredits'];
			if (empty($__tree['dbtechCredits']['children'])) { $__tree['dbtechCredits']['children'] = []; }

			$__navTemp = [
		'title' => \XF::phrase('nav.dbtechCreditsTransactions'),
		'href' => $__templater->func('link', array('dbtech-credits', ), false),
		'attributes' => [],
	];
			if ($__navTemp) {
				$__tree['dbtechCredits']['children']['dbtechCreditsTransactions'] = $__navTemp;
				$__flat['dbtechCreditsTransactions'] =& $__tree['dbtechCredits']['children']['dbtechCreditsTransactions'];
			}

		}
	}



	return [
		'tree' => $__tree,
		'flat' => $__flat
	];
};