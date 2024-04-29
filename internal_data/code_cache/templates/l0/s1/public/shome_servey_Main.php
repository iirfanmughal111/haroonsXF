<?php
// FROM HASH: 276cee0cd3b157c1662ae4bbd70923c6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('shome_servey_Main.less');
	$__finalCompiled .= '
<div class="box-block">
			<h1 class = "rewards">' . 'Rewards Program' . '</h1>
                <div class="container home-content2">
					  <div class="row joinwork">						  
						 <div class="col-md-5 col-sm-12"> 
							 
							 <div class="banner-left">
													
								<p class = "join">' . 'Join +300,000 members<br> that help each other <br>to' . '<span>' . ' make money online' . '</span></p>
								<a href="' . $__templater->func('link', array('register', ), true) . '" class="joinbutton">' . 'Start earning!' . '</a>
							</div>
						</div>		
						
						 <div class="inline-md-7 inline-sm-12"> 
                        <div class="video-box">
                            <h2>' . 'How it ' . '<span>' . 'works' . '</span>?</h2>
                                
                           <div class="video-box-script">
  <div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/806117119?h=798ade4d90&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="start a freelance business module 8"></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>
							</div>

                                
                        
                        </div>
                            
                    </div>
                    
					</div>	 
                </div>
                <!-- end container -->
            </div>';
	return $__finalCompiled;
}
);