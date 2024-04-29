<?php
// FROM HASH: faa7a656d394f8e01411bbe22bb9e552
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('snog_tv.less');
	$__finalCompiled .= '

<li class="block-row block-row--separated ' . ($__templater->method($__vars['post'], 'isIgnored', array()) ? 'is-ignored' : '') . ' js-inlineModContainer" data-author="' . ($__templater->escape($__vars['post']['User']['username']) ?: $__templater->escape($__vars['post']['username'])) . '">
	<div class="contentRow ' . ((!$__templater->method($__vars['post'], 'isVisible', array())) ? 'is-deleted' : '') . '">

		<span class="contentRow-figure">
			<img class="episodeImage episodeImage--search" src="' . $__templater->escape($__templater->method($__vars['post']['TVPost'], 'getEpisodeImageUrl', array('s', ))) . '" />
		</span>
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('threads/post', $__vars['post']['Thread'], array('post_id' => $__vars['post']['post_id'], ), ), true) . '">' . ($__templater->func('prefix', array('thread', $__vars['post']['Thread'], ), true) . $__templater->func('highlight', array($__vars['post']['Thread']['title'], $__vars['options']['term'], ), true)) . '</a>
			</h3>

			<div>
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

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					';
	if (($__vars['options']['mod'] == 'post') AND $__templater->method($__vars['post'], 'canUseInlineModeration', array())) {
		$__finalCompiled .= '
						<li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['post']['post_id'],
			'class' => 'js-inlineModToggle',
			'data-xf-init' => 'tooltip',
			'title' => 'Select for moderation',
			'_type' => 'option',
		))) . '</li>
					';
	}
	$__finalCompiled .= '
					<li>' . $__templater->func('username_link', array($__vars['post']['User'], false, array(
		'defaultname' => $__vars['post']['username'],
	))) . '</li>
					<li>' . 'Post #' . $__templater->func('number', array($__vars['post']['position'] + 1, ), true) . '' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['post']['post_date'], array(
	))) . '</li>
					<li>' . 'Forum' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('forums', $__vars['post']['Thread']['Forum'], ), true) . '">' . $__templater->escape($__vars['post']['Thread']['Forum']['title']) . '</a></li>
				</ul>
			</div>
		</div>
	</div>
</li>';
	return $__finalCompiled;
}
);