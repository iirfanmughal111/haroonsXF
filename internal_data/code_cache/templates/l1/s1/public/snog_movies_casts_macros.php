<?php
// FROM HASH: 43390ff1ac56597b4003010e7cf5bd70
return array(
'macros' => array('cast_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'movie' => '!',
		'casts' => '!',
		'page' => '1',
		'hasMore' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<ul class="listPlain listColumns listColumns--3 listColumns--spaced js-movieCastList' . $__templater->escape($__vars['movie']['tmdb_id']) . '">
		';
	if ($__templater->isTraversable($__vars['casts'])) {
		foreach ($__vars['casts'] AS $__vars['cast']) {
			$__finalCompiled .= '
			<li>
				' . $__templater->callMacro(null, 'cast_info', array(
				'cast' => $__vars['cast'],
			), $__vars) . '
			</li>
		';
		}
	}
	$__finalCompiled .= '
	</ul>

	';
	if ($__vars['hasMore']) {
		$__finalCompiled .= '
		<div class="block-footer js-movieCastLoadMore' . $__templater->escape($__vars['movie']['tmdb_id']) . '">
			<span class="block-footer-controls">
				' . $__templater->button('
					' . 'More' . $__vars['xf']['language']['ellipsis'] . '
				', array(
			'href' => $__templater->func('link', array('movies/casts', $__vars['movie'], array('page' => $__vars['page'] + 1, ), ), false),
			'data-xf-click' => 'inserter',
			'data-append' => '.js-movieCastList' . $__vars['movie']['tmdb_id'],
			'data-replace' => '.js-movieCastLoadMore' . $__vars['movie']['tmdb_id'],
		), '', array(
		)) . '
			</span>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'cast_info' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'cast' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="block-row block-row--separated">
		<div class="contentRow movieTab--casts">
			<span class="contentRow-figure contentRow-figure--fixedMedium contentRow-figure--fixedMedium contentRow-figure--fluidWidth">
				<img src="' . ($__templater->escape($__vars['cast']['Person']['image_url']) ?: $__templater->func('base_url', array($__templater->func('property', array('snog_movies_castPersonSmallImageUrl', ), false), ), true)) . '" />
			</span>

			<div class="contentRow-main">
				' . $__templater->callMacro(null, 'person_menu', array(
		'person' => $__vars['cast']['Person'],
	), $__vars) . '
				<h3 class="block-textHeader">
					' . $__templater->escape($__vars['cast']['Person']['name']) . '
				</h3>
				' . $__templater->escape($__vars['cast']['character']) . '
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'person_menu' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'person' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
						';
	if ($__templater->method($__vars['person'], 'canUpdate', array())) {
		$__compilerTemp1 .= '
							<a href="' . $__templater->func('link', array('movies/person/update', $__vars['person'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Update person image & data' . '</a>
						';
	}
	$__compilerTemp1 .= '
					';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="contentRow-extra">

			' . $__templater->button('
				' . $__templater->fontAwesome('fa-cog', array(
		)) . '
			', array(
			'class' => 'button--link menuTrigger',
			'data-xf-click' => 'menu',
			'aria-label' => 'More options',
			'aria-expanded' => 'false',
			'aria-haspopup' => 'true',
		), '', array(
		)) . '

			<div class="menu" data-menu="menu" aria-hidden="true">
				<div class="menu-content">
					' . $__compilerTemp1 . '
				</div>
			</div>
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
	$__finalCompiled .= '

' . '

';
	return $__finalCompiled;
}
);