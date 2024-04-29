<?php
// FROM HASH: cf4f0a0d5aafb480dd0978eb5dbc3276
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<style> .p-body-header{display:none} </style>
';
	if ($__vars['total']) {
		$__finalCompiled .= '
	<div class="p-tournaments">
		<h2 class="tabs" data-xf-init="tabs" data-state="replace" role="tablist">
			<a href="#" class="tabs-tab is-active" role="tab" aria-controls="tournament-' . $__templater->escape($__vars['firstTournament']['tourn_id']) . '">
				<img src="' . $__templater->escape($__templater->method($__vars['firstTournament'], 'getImgUrl', array(true, 'icon', ))) . '" alt="Fortnite" />
			</a>
			';
		if ($__templater->isTraversable($__vars['tournaments'])) {
			foreach ($__vars['tournaments'] AS $__vars['tournament']) {
				$__finalCompiled .= '
				<a href="#" class="tabs-tab" id="tournament-' . $__templater->escape($__vars['tournament']['tourn_id']) . '" role="tab">
					<img src="' . $__templater->escape($__templater->method($__vars['tournament'], 'getImgUrl', array(true, 'icon', ))) . '" alt="League" />
				</a>
			';
			}
		}
		$__finalCompiled .= '
		</h2>
		<ul class="tabPanes widget--tab">
			';
		if ($__vars['firstTournament']) {
			$__finalCompiled .= '
				<li class="is-active" role="tabpanel" id="tournament-1">
					<div class="p-tournament__bar">
						<div>
							<dl class="pairs pairs--inline">
								<dt>
									<span class="desktop">' . 'tourney_1_start' . '</span>
								</dt>
									<dd id="tournamentstartTimer-' . $__templater->escape($__vars['firstTournament']['tourn_id']) . '"></dd>
							</dl>
							<dl class="pairs pairs--inline">
								<dt>
									<span class="desktop">' . 'tourney_1_end' . '</span>
								</dt>
								<dd id="tournamentendTimer-' . $__templater->escape($__vars['firstTournament']['tourn_id']) . '"></dd>
							</dl>
						</div>
					</div>
					<div class="p-tournament__hero p-tournament__hero--one" style="background-image: url(\'' . $__templater->escape($__templater->method($__vars['firstTournament'], 'getImgUrl', array(true, 'header', ))) . '\'">
						<h2>
							<span class="desktop">' . $__templater->escape($__vars['firstTournament']['tourn_title']) . '</span>
						</h2>
						<div id="tournamentTimer-' . $__templater->escape($__vars['firstTournament']['tourn_id']) . '" class="p-tournament__timer" style="display: none;"></div>
						';
			$__vars['tournamentTimer'] = $__templater->preEscaped($__templater->escape($__templater->method($__vars['firstTournament'], 'getStartDate', array('Y-m-d H:i', ))));
			$__finalCompiled .= '
						<div id="tournamentStart-' . $__templater->escape($__vars['firstTournament']['tourn_id']) . '">
							<a href="' . $__templater->func('link', array('uptourn/details', $__vars['firstTournament'], ), true) . '" class="button button--primary" data-xf-click="overlay" data-follow-rmacroedirects="on">
								<span class="desktop">' . 'Tournament detail' . '</span>
							</a>
						</div>
					</div>
					<div class="p-tournament__content">
						<div class="p-tournament__prizes">
							<div class="p-tournament__content-header">
								<i class="fas fa-star icon"></i>
								<span class="desktop">' . 'tourney_1_prizes' . '</span>
							</div>
							<div class="p-tournament__content-body">
								<div class="text-center">
									<div class="p-tournament__prize-total">
										<i class="fas fa-coins"></i> ' . $__templater->escape($__vars['firstTournament']['tourn_main_price']) . ' 
									</div>
									<ul>
										';
			if ($__templater->isTraversable($__vars['firstTournament']['tourn_prizes'])) {
				foreach ($__vars['firstTournament']['tourn_prizes'] AS $__vars['key'] => $__vars['prize']) {
					$__finalCompiled .= '
											<li>
												<dl class="pairs pairs--justified">
													<dt>
														<span class="desktop">' . $__templater->escape($__vars['key']) . '</span>
													</dt>
													<dd>' . $__templater->escape($__vars['prize']) . '</dd>
												</dl>
											</li>
										';
				}
			}
			$__finalCompiled .= '
									</ul>
								</div>
							</div>
						</div>
						<div class="p-tournament__info">
							<div class="p-tournament__content-header">
							
								';
			if (!$__vars['trounamentUsers'][$__vars['firstTournament']['tourn_id']]['is_register']) {
				$__finalCompiled .= '
								<span class="desktop">' . 'You have been registered' . '</span>
								';
			}
			$__finalCompiled .= '
								
								
								';
			if (($__vars['firstTournament']['tourn_startdate'] > $__vars['ctime']) AND $__vars['trounamentUsers'][$__vars['firstTournament']['tourn_id']]['is_register']) {
				$__finalCompiled .= '
										<a href="' . $__templater->func('link', array('uptourn/registertour', $__vars['firstTournament'], ), true) . '" class="button button--primary" data-xf-click="overlay" data-follow-rmacroedirects="on">
											<span class="desktop">' . 'tourney_2_button' . '</span>
										</a>
									';
			}
			$__finalCompiled .= '
							</div>
							<div class="p-tournament__content-body">
								<div> 
									
									
									' . $__templater->callMacro('registeruserlist', 'userlist', array(
				'tour' => $__vars['firstTournament'],
				'users' => $__vars['trounamentUsers'][$__vars['firstTournament']['tourn_id']]['users'],
			), $__vars) . '
								</div>
							</div>
						</div>
					</div>
				</li>
				         ';
			$__templater->inlineJs('
                    $(document).ready(function() {
					var tzone = moment.tz.guess();		 
                    var tournamentOneEnds = moment.tz("' . $__vars['tournamentTimer'] . '", tzone);
							 
				                   
				
					var tournamentOneEndsDate = moment.tz("' . $__templater->method($__vars['firstTournament'], 'getEndDate', array('Y-m-d H:i', )) . '", tzone);
					
$("#tournamentstartTimer-' . $__vars['firstTournament']['tourn_id'] . '").text(moment.tz(tournamentOneEnds._d, tzone).format("DD/MM/YYYY hh:mm A z"));
$("#tournamentendTimer-' . $__vars['firstTournament']['tourn_id'] . '").text(moment.tz(tournamentOneEndsDate._d, tzone).format("DD/MM/YYYY hh:mm A z"));			 
							 
							 
							 
                    $id=' . $__vars['firstTournament']['tourn_id'] . ';				
                    $("#tournamentTimer-' . $__vars['firstTournament']['tourn_id'] . '").countdown(tournamentOneEnds._d, function(event) {
                    var $this = $(this).html(event.strftime(\'\'
                    + \'<div id="day_' . $__vars['firstTournament']['tourn_id'] . '"><span class="number">%D</span><span class="type">days</span></div>\'
                    + \'<div id="hour_' . $__vars['firstTournament']['tourn_id'] . '"><span class="number">%H</span><span class="type">hrs</span></div>\'
                    + \'<div id="min_' . $__vars['firstTournament']['tourn_id'] . '"><span class="number">%M</span><span class="type">mins</span></div>\'
                    + \'<div id="sec_' . $__vars['firstTournament']['tourn_id'] . '"><span class="number">%S</span><span class="type">secs</span></div>\'));
                    });

                    var minute = $("#min_' . $__vars['firstTournament']['tourn_id'] . ' .number").html();
                    var second = $("#sec_' . $__vars['firstTournament']['tourn_id'] . ' .number").html();




                    if((minute == \'00\') && (second == \'00\')) {
                    $(\'#tournamentTimer-' . $__vars['firstTournament']['tourn_id'] . '\').hide();
                    $(\'#tournamentStart-' . $__vars['firstTournament']['tourn_id'] . '\').show();
                    } else {
                    $(\'#tournamentTimer-' . $__vars['firstTournament']['tourn_id'] . '\').show();
                    $(\'#tournamentStart-' . $__vars['firstTournament']['tourn_id'] . '\').hide();
                    }			

                    });
                ');
			$__finalCompiled .= '
			';
		}
		$__finalCompiled .= '
			';
		if ($__templater->isTraversable($__vars['tournaments'])) {
			foreach ($__vars['tournaments'] AS $__vars['tournament']) {
				$__finalCompiled .= '
				<li role="tabpanel" aria-labelledby="tournament-' . $__templater->escape($__vars['tournament']['tourn_id']) . '">
					<div class="p-tournament__bar">
						<div>
							<dl class="pairs pairs--inline">
								<dt>
									<span>' . 'tourney_1_start' . '</span>
								</dt>
								<dd id="tournamentstartTimer-' . $__templater->escape($__vars['tournament']['tourn_id']) . '"></dd>
							</dl>
							<dl class="pairs pairs--inline">
								<dt>
									<span>' . 'tourney_1_end' . '</span>
								</dt>
								<dd id="tournamentendTimer-' . $__templater->escape($__vars['tournament']['tourn_id']) . '"></dd>
							</dl>
						</div>
					</div>
					<div class="p-tournament__hero p-tournament__hero--two" style="background-image: url(\'' . $__templater->escape($__templater->method($__vars['tournament'], 'getImgUrl', array(true, 'header', ))) . '\'">
						<h2>
							<span class="desktop">' . $__templater->escape($__vars['tournament']['tourn_title']) . '</span>
						</h2>
						<div id="tournamentTimer-' . $__templater->escape($__vars['tournament']['tourn_id']) . '" class="p-tournament__timer" style="display: none;"></div>
						<div id="tournamentStart-' . $__templater->escape($__vars['tournament']['tourn_id']) . '">
							<a href="' . $__templater->func('link', array('uptourn/details', $__vars['tournament'], ), true) . '" class="button button--primary" data-xf-click="overlay" data-follow-rmacroedirects="on">
								<span class="desktop">' . 'Tournament detail' . '</span>
							</a>
						</div>
					</div>
					<div class="p-tournament__content">
						<div class="p-tournament__prizes">
							<div class="p-tournament__content-header">
								<i class="fas fa-star icon"></i>
								<span class="desktop">' . 'tourney_1_prizes' . '</span>
							</div>
							<div class="p-tournament__content-body">
								<div class="text-center">
									<div class="p-tournament__prize-total">
										<i class="fas fa-coins"></i> ' . $__templater->escape($__vars['tournament']['tourn_main_price']) . ' 
									</div>
									<ul>
										';
				if ($__templater->isTraversable($__vars['tournament']['tourn_prizes'])) {
					foreach ($__vars['tournament']['tourn_prizes'] AS $__vars['key'] => $__vars['prize']) {
						$__finalCompiled .= '
											<li>
												<dl class="pairs pairs--justified">
													<dt>
														<span class="desktop">' . $__templater->escape($__vars['key']) . '</span>
													</dt>
													<dd>' . $__templater->escape($__vars['prize']) . '</dd>
												</dl>
											</li>
										';
					}
				}
				$__finalCompiled .= '
									</ul>
								</div>
							</div>
						</div>
						<div class="p-tournament__info">
                                                    <div class="p-tournament__content-header">
								
								';
				if (!$__vars['trounamentUsers'][$__vars['tournament']['tourn_id']]['is_register']) {
					$__finalCompiled .= '
								<span class="desktop">' . 'You have been registered' . '</span>
								';
				}
				$__finalCompiled .= '							
														
														
								';
				if (($__vars['tournament']['tourn_startdate'] > $__vars['ctime']) AND $__vars['trounamentUsers'][$__vars['tournament']['tourn_id']]['is_register']) {
					$__finalCompiled .= '
										<a href="' . $__templater->func('link', array('uptourn/registertour', $__vars['tournament'], ), true) . '" class="button button--primary" data-xf-click="overlay" data-follow-rmacroedirects="on">
											<span class="desktop">' . 'tourney_2_button' . '</span>
										</a>
									';
				}
				$__finalCompiled .= '
							</div>
							<div class="p-tournament__content-body">
								<div>
									 
									
									' . $__templater->callMacro('registeruserlist', 'userlist', array(
					'tour' => $__vars['tournament'],
					'users' => $__vars['trounamentUsers'][$__vars['tournament']['tourn_id']]['users'],
				), $__vars) . '
								</div>
							</div>
						</div>
					</div>
				</li>
				';
				$__templater->inlineJs('
                    $(document).ready(function() {
					
					var tzone = moment.tz.guess();

                    var tournamentOneEnds = moment.tz("' . $__templater->method($__vars['tournament'], 'getStartDate', array('Y-m-d H:i', )) . '", tzone);
				
					var tournamentOneEndsDate = moment.tz("' . $__templater->method($__vars['tournament'], 'getEndDate', array('Y-m-d H:i', )) . '", tzone);
					
$("#tournamentstartTimer-' . $__vars['tournament']['tourn_id'] . '").text(moment.tz(tournamentOneEnds._d, tzone).format("DD/MM/YYYY hh:mm A z"));
$("#tournamentendTimer-' . $__vars['tournament']['tourn_id'] . '").text(moment.tz(tournamentOneEndsDate._d, tzone).format("DD/MM/YYYY hh:mm A z"));				
					
                    $("#tournamentTimer-' . $__vars['tournament']['tourn_id'] . '").countdown(tournamentOneEnds._d, function(event) {
                    var $this = $(this).html(event.strftime(\'\'
                    + \'<div id="day_' . $__vars['tournament']['tourn_id'] . '"><span class="number">%D</span><span class="type">days</span></div>\'
                    + \'<div id="hour_' . $__vars['tournament']['tourn_id'] . '"><span class="number">%H</span><span class="type">hrs</span></div>\'
                    + \'<div id="min_' . $__vars['tournament']['tourn_id'] . '"><span class="number">%M</span><span class="type">mins</span></div>\'
                    + \'<div id="sec_' . $__vars['tournament']['tourn_id'] . '"><span class="number">%S</span><span class="type">secs</span></div>\'));
                    });

                    var minute' . $__vars['tournament']['tourn_id'] . ' = $("#min_' . $__vars['tournament']['tourn_id'] . ' .number").html();
                    var second' . $__vars['tournament']['tourn_id'] . ' = $("#sec_' . $__vars['tournament']['tourn_id'] . ' .number").html();


                    if((minute' . $__vars['tournament']['tourn_id'] . ' == \'00\') && (second' . $__vars['tournament']['tourn_id'] . ' == \'00\')) {
                    $(\'#tournamentTimer-' . $__vars['tournament']['tourn_id'] . '\').hide();
                    $(\'#tournamentStart-' . $__vars['tournament']['tourn_id'] . '\').show();
                    } else {
                    $(\'#tournamentTimer-' . $__vars['tournament']['tourn_id'] . '\').show();
                    $(\'#tournamentStart-' . $__vars['tournament']['tourn_id'] . '\').hide();

                    }			

                    });
                ');
				$__finalCompiled .= '
			';
			}
		}
		$__finalCompiled .= '
		</ul>
	</div>
';
	} else {
		$__finalCompiled .= '
' . $__templater->form('
	

	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
			' . 'No tournament is created yet' . '
			
			', array(
			'rowtype' => 'confirm',
		)) . '
		</div>
	</div>
	', array(
			'action' => '',
			'ajax' => 'true',
			'class' => 'block',
		)) . '
	
';
	}
	$__finalCompiled .= '



<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
';
	$__templater->modifySideNavHtml(null, '
	<div class="p-body-main-sidebar">
		<form action="" method="post" class="p-body-search" data-xf-init="quick-search">
			<input type="text" class="input" name="keywords" id="dna" placeholder="Search…" aria-label="Search" data-menu-autofocus="true">
				<button type="button">
					<i class="fa--xf fas fa-search" aria-hidden="true"></i>
				</button>
				<input type="hidden" name="_xfToken" value="' . $__templater->escape($__vars['visitor']['csrf_token_page']) . '" />
			</form>
			' . $__templater->includeTemplate('_widget_sidebar_games', $__vars) . '
		</div>
	', 'replace');
	return $__finalCompiled;
}
);