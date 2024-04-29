<?php
// FROM HASH: 232285ae12da4c479657a201e1f37432
return array(
'macros' => array('stats' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'stats' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeCss('nick97_stats.less');
	$__finalCompiled .= '

	<div class="stats-p-body-section">
		<div class="p-body-section-header">
			<div class="statsContainer">
				<div class="statsBox">
					<h2>' . 'Stats' . '</h2>
				</div>

				' . '
			</div>
		</div>
	</div>

	<div class="myContainer">
		<div class="myBox">
			<h1 class="myBox-heading"><i class="fa fa-tv"></i> ' . 'TV time' . '</h1>
			<div class="myContainer1">
				<div class="myBox-body">
					<h2>
						' . $__templater->escape($__vars['stats']['moviesTime']['months']) . '
					</h2>
					<h5>
						' . 'MONTH' . '
					</h5>
				</div>
				<div class="myBox-body">
					<h2>
						' . $__templater->escape($__vars['stats']['moviesTime']['days']) . '
					</h2>
					<h5>
						' . 'DAYS' . '
					</h5>
				</div>

				<div class="myBox-body">
					<h2>
						' . $__templater->escape($__vars['stats']['moviesTime']['hours']) . '
					</h2>
					<h5>
						' . 'HOURS' . '
					</h5>
				</div>
			</div>
		</div>
		<div class="myBox">
			<h1 class="myBox-heading"><i class="fa fa-tv"></i> ' . 'Episode watched' . '</h1>
			<div class="myBox-body">
				<h1>
					' . $__templater->escape($__vars['stats']['episodesWatched']) . '
				</h1>
			</div>
		</div>
		<div class="myBox">
			<h1 class="myBox-heading"><i class="fa fa-film"></i> ' . 'Movie time' . '</h1>
			<div class="myContainer1">
				<div class="myBox-body">
					<h2>
						' . $__templater->escape($__vars['stats']['episodesTime']['months']) . '
					</h2>
					<h5>
						' . 'MONTH' . '
					</h5>
				</div>
				<div class="myBox-body">
					<h2>
						' . $__templater->escape($__vars['stats']['episodesTime']['days']) . '
					</h2>
					<h5>
						' . 'DAYS' . '
					</h5>
				</div>

				<div class="myBox-body">
					<h2>
						' . $__templater->escape($__vars['stats']['episodesTime']['hours']) . '
					</h2>
					<h5>
						' . 'HOURS' . '
					</h5>
				</div>
			</div>
		</div>
		<div class="myBox">
			<h1 class="myBox-heading"><i class="fa fa-film"></i> ' . 'Movie watched' . '</h1>
			<div class="myBox-body">
				<h1>
					' . $__templater->escape($__vars['stats']['moviesWatched']) . '
				</h1>
			</div>
		</div>
	</div>

';
	return $__finalCompiled;
}
),
'movies' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'movies' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeCss('nick97_watch_list_movies.less');
	$__finalCompiled .= '

	<div class="p-body-section">
		<div class="p-body-section-header">
			<div class="container">
				<div class="box">
					<h2>' . 'Movies' . '</h2>
				</div>

				';
	if ($__templater->func('count', array($__vars['movies'], ), false) > 1) {
		$__finalCompiled .= '

					<div class="right_watch_list box">
						<h2><i class="fas fa-chevron-right"></i></h2>
					</div>

				';
	}
	$__finalCompiled .= '
			</div>
		</div>
	</div>

	';
	if ($__templater->func('count', array($__vars['movies'], ), false) > 0) {
		$__finalCompiled .= '


		<div id="container">


			<div id="list-container">



				<div class=\'list watch_list\' style="">

					';
		if ($__templater->isTraversable($__vars['movies'])) {
			foreach ($__vars['movies'] AS $__vars['movie']) {
				$__finalCompiled .= '

						<div class=\'item\'>
							<a class="carousel-item-image" href="' . $__templater->func('link', array('threads', $__vars['movie']['Thread'], ), true) . '">
								<img src="' . $__templater->escape($__templater->method($__vars['movie'], 'getImageUrl', array())) . '" style="width: 185px; height: 278px; border-radius: 6px;" />
							</a>
						</div>

					';
			}
		}
		$__finalCompiled .= '

				</div>


			</div>

		</div>

		';
	} else {
		$__finalCompiled .= '

		<div class="empty">' . 'There are no movies in this Watch List.' . '</div>

	';
	}
	$__finalCompiled .= '


	';
	$__templater->inlineJs('

		$(document).ready(function(){
		$(".right_watch_list").click(function(){
		var item_width = $(\'#list-container div.watch_list div.item\').width(); 
		var left_value = item_width * (-1); 
		var left_indent = parseInt($(\'#list-container div.watch_list\').css(\'left\')) - item_width;
		$(\'#list-container div.watch_list \').animate({\'left\' : left_indent}, 100, function () {
		$(\'#list-container div.watch_list div.item:last\').after($(\'#list-container div.watch_list div.item:first\'));                  
		$(\'#list-container div.watch_list\').css({\'left\' : left_value});
		});
		});

		$(".left_watch_list").click(function(){
		var item_width = $(\'#list-container div.watch_list div.item\').width(); 
		var right_value = item_width * (+1); 
		var right_indent = parseInt($(\'#list-container div.watch_list\').css(\'right\')) + item_width;
		$(\'#list-container div.watch_list \').animate({\'right\' : right_indent}, 100, function () {
		$(\'#list-container div.watch_list div.item:first\').before($(\'#list-container div.watch_list div.item:last\'));                  
		$(\'#list-container div.watch_list\').css({\'right\' : right_value});
		});
		});
		});

	');
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
),
'tvShow' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'tvShows' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeCss('nick97_watch_list_tv_shows.less');
	$__finalCompiled .= '


	<div class="p-body-section1">
		<div class="p-body-section-header1">
			<div class="container1">
				<div class="box1">
					<h2>' . 'TV & Shows' . '</h2>
				</div>

				';
	if ($__templater->func('count', array($__vars['tvShows'], ), false) > 1) {
		$__finalCompiled .= '

					<div class="right_watch_list1 box1">
						<h2><i class="fas fa-chevron-right"></i></h2>
					</div>

				';
	}
	$__finalCompiled .= '
			</div>
		</div>
	</div>

	';
	if ($__templater->func('count', array($__vars['tvShows'], ), false) > 0) {
		$__finalCompiled .= '

		<div id="container1">

			' . '

			<div id="list-container1">


				<div class=\'list1 watch_list1\' style="">

					';
		if ($__templater->isTraversable($__vars['tvShows'])) {
			foreach ($__vars['tvShows'] AS $__vars['movie']) {
				$__finalCompiled .= '

						<div class=\'item1\'>
							<a class="carousel-item-image" href="' . $__templater->func('link', array('threads', $__vars['movie']['Thread'], ), true) . '">
								<img src="' . $__templater->escape($__templater->method($__vars['movie'], 'getImageUrl', array())) . '" style="width: 185px; height: 278px; border-radius: 6px;" />
							</a>
						</div>

					';
			}
		}
		$__finalCompiled .= '		

				</div>


			</div>

		</div>

		';
	} else {
		$__finalCompiled .= '

		<div class="empty">' . 'There are no Tv Shows in this Watch List.' . '</div>

	';
	}
	$__finalCompiled .= '


	';
	$__templater->inlineJs('

		$(document).ready(function(){
		$(".right_watch_list1").click(function(){
		var item_width = $(\'#list-container1 div.watch_list1 div.item1\').width(); 
		var left_value = item_width * (-1); 
		var left_indent = parseInt($(\'#list-container1 div.watch_list1\').css(\'left\')) - item_width;
		$(\'#list-container1 div.watch_list1 \').animate({\'left\' : left_indent}, 100, function () {
		$(\'#list-container1 div.watch_list1 div.item1:last\').after($(\'#list-container1 div.watch_list1 div.item1:first\'));                  
		$(\'#list-container1 div.watch_list1\').css({\'left\' : left_value});
		});
		});

		$(".left_watch_list1").click(function(){
		var item_width = $(\'#list-container1 div.watch_list1 div.item1\').width(); 
		var right_value = item_width * (+1); 
		var right_indent = parseInt($(\'#list-container1 div.watch_list1\').css(\'right\')) + item_width;
		$(\'#list-container1 div.watch_list1 \').animate({\'right\' : right_indent}, 100, function () {
		$(\'#list-container1 div.watch_list1 div.item1:first\').before($(\'#list-container1 div.watch_list1 div.item1:last\'));                  
		$(\'#list-container1 div.watch_list1\').css({\'right\' : right_value});
		});
		});
		});

	');
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