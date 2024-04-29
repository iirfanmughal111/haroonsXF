<?php
// FROM HASH: 0ad905339237e883b16351b48ee1bf8a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css" />
';
	$__templater->includeJs(array(
		'src' => 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js',
	));
	$__finalCompiled .= '
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
';
	$__templater->inlineCss('
@charset "UTF-8";

' . '
.p-body-header
{
	display: none;
}
	
.YouTubePopUp-Wrap{
    position:fixed;
    width:100%;
    height:100%;
    background-color:#000;
    background-color:rgba(0,0,0,0.8);
    top:0;
    left:0;
    z-index:9999999999999;
}

.YouTubePopUp-animation{
    opacity: 0;
    -webkit-animation-duration: 0.5s;
    animation-duration: 0.5s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
    -webkit-animation-name: YouTubePopUp;
    animation-name: YouTubePopUp;
}

@-webkit-keyframes YouTubePopUp {
    0% {
        opacity: 0;
    }

    100% {
        opacity: 1;
    }
}

@keyframes YouTubePopUp {
    0% {
        opacity: 0;
    }

    100% {
        opacity: 1;
    }
}

body.logged-in .YouTubePopUp-Wrap{ / For WordPress /
    top:32px;
    z-index:99998;
}

.YouTubePopUp-Content{
    max-width: 846px;
    display:block;
    margin:0 auto;
    height:100%;
    position:relative;
}

.YouTubePopUp-Content iframe{
    max-width:100% !important;
    width:100% !important;
    display:block !important;
    height:480px !important;
    border:none !important;
    position:absolute;
    top: 0;
    bottom: 0;
    margin: auto 0;
}

.YouTubePopUp-Hide{
    -webkit-animation-duration: 0.5s;
    animation-duration: 0.5s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
    -webkit-animation-name: YouTubePopUpHide;
    animation-name: YouTubePopUpHide;
}

@-webkit-keyframes YouTubePopUpHide {
    0% {
        opacity: 1;
    }

    100% {
        opacity: 0;
    }
}

@keyframes YouTubePopUpHide {
    0% {
        opacity: 1;
    }

    100% {
        opacity: 0;
    }
}
	.video-title-text {
width: 100%;
padding: 0px;
text-align: center;
margin: 0px auto;
color: white;
font-size: 12px;
	}
.YouTubePopUp-Close{
    position:absolute;
    top:0;
    cursor:pointer;
    bottom: 475px;
    right: -57px;
    margin:auto 0;
    width:24px;
    height:24px;
    background:url("styles/img/close.png") no-repeat;
    background-size:24px 24px;
    -webkit-background-size:24px 24px;
    -moz-background-size:24px 24px;
    -o-background-size:24px 24px;
}

.YouTubePopUp-Close:hover{
    opacity:0.5;
}

@media all and (max-width: 1050px) and (min-width: 10px){
    .YouTubePopUp-Close{
        bottom:550px;
        right:0px
    }
    .YouTubePopUp-Content {
        max-width: 85%;
    }
}
@media all and (max-width: 768px) and (min-width: 10px){
    .YouTubePopUp-Content{
        max-width:90%;
    }
}

@media all and (max-width: 600px) and (min-width: 10px){
    .YouTubePopUp-Content iframe{
        height:320px !important;
    }

    .YouTubePopUp-Close{
        bottom:362px;
    }
}

@media all and (max-width: 480px) and (min-width: 10px){
    .YouTubePopUp-Content iframe{
        height:220px !important;
    }

    .YouTubePopUp-Close{
        bottom:262px;
        right:0px
    }
}	

	.block-container{
		background: #000;
	}
    .bar-icon{
        display: none;
    }
.navbar {
	  overflow: hidden;
	 color: #fff;
	  border-bottom: 2px solid;
	  background: rgb(19 18 18);
	  transition: ease-in .15s all;
	}

	.nav-inner {
	  max-width: 800px;
		margin-left: auto;
		margin-right: auto;
		width: fit-content;
		transition: max-width .2s;
		height: 43px;
		display: flex;
		align-items: center;
		text-decoration: none;
	}
	.nav-inner a {
		color:rgba(255,255,255,0.7);
		text-decoration: none;
		color:#fff;
		 padding: 12px 16px;
	}

	.subnav {
	  float: left;
	  overflow: hidden;
	}

	.subnav .subnavbtn {  
	  border: none;
	  outline: none;
	  color: white;
	  padding: 12px 16px;
	  background-color: inherit;
	  font-family: inherit;
	  margin: 0;

	}

	.navbar a:hover, .subnav:hover .subnavbtn {
	  background-color: #fff;
	  color:black;
	}

	.subnav-content {
	   display: none;
		background-color: black;
		width: 11.60em;
		z-index: 1;
		position: absolute;
	}

	.subnav-content a {
		 color: white;
		text-decoration: none;
		display: block;
	    padding: 10px 0px 5px 10px;
	    border-bottom: 1px solid;
	}

	.subnav-content a:hover {
	  background-color: #eee;
	  color: black;
	}

	.subnav:hover .subnav-content {
	  display: block;
	}

.main-body{
    background:black;
}  
.main-inner-body{
    margin:10px;
}
.slider1{
    display: flex;
 
    overflow: hidden;
    width:100%;
}
 .featured-video{
	  width: 100%;
	  height: 100%;
	  margin:0;
	}
.slider2{
    display: flex;
   
    overflow: hidden;
    width:100%;
}
.slider3,.slider4{
    display: flex;

    overflow: hidden;
    width:100%;
}
.arrowL1{
    position: absolute;
    font-size: 40px;
    color:#fff;
    top: 17px;
    left: 0px;
    background: black;
    padding-top: 45px;
    padding-bottom: 45px;
    padding-left: 10px;
    padding-right: 10px;
    opacity: 0.7;
    cursor: pointer;
}
.arrowR1{
    position: absolute;
    font-size: 40px;
    color:#fff;
    top: 17px;
    right: 0px;
    background: black;
    padding-top: 45px;
    padding-bottom: 45px;
    padding-left: 10px;
    padding-right: 10px;
    opacity: 0.7;
    cursor: pointer;
}
.arrowL2{
    position: absolute;
    font-size: 40px;
    color:#fff;
    top: 17px;
    left: 0px;
    background: black;
    padding-top: 45px;
    padding-bottom: 45px;
    padding-left: 10px;
    padding-right: 10px;
    opacity: 0.7;
    cursor: pointer;
}
.arrowR2{
    position: absolute;
    font-size: 40px;
    color:#fff;
    top: 17px;
    right: 0px;
    background: black;
    padding-top: 45px;
    padding-bottom: 45px;
    padding-left: 10px;
    padding-right: 10px;
    opacity: 0.7;
    cursor: pointer;
}
.arrowL3,.arrowL4{
    position: absolute;
    font-size: 40px;
    color:#fff;
    top: 17px;
    left: 0px;
    background: black;
    padding-top: 45px;
    padding-bottom: 45px;
    padding-left: 10px;
    padding-right: 10px;
    opacity: 0.7;
    cursor: pointer;
}
.arrowR3,.arrowR4{
    position: absolute;
    font-size: 40px;
    color:#fff;
    top: 17px;
    right: 0px;
    background: black;
    padding-top: 45px;
    padding-bottom: 45px;
    padding-left: 10px;
    padding-right: 10px;
    opacity: 0.7;
    cursor: pointer;
}
.thumbnail{
    display: inline-flex;
    width:193px;
object-fit: contain;
	height:135px;
}
#mainPlayer {
    text-align: center;
    padding: 5px;
    margin-bottom: 15px;
  }
  
  #mainPlayer p {
    font-size: 20px;
    height: 600px;
    padding: 0;
    margin: 0;
  }
  .featureV{
    width: 90%;
    height: 600px;
    border: none;
  }
  .slider-heading{
    font-size: 20px;
    color: #fff;
    margin-top: 0px;    
    margin-left: 0.5%;
	margin-bottom: 0px;
  }
  .slider-list{
    display: flex;
    list-style: none;
    padding-left: 0px;
    overflow: hidden;
	   width: 100%;
  }
  .slider-list-item{
    min-width: 18%;
    margin-left: 3px;
    margin-right: 3px;
	max-width: 18%;
  }
  .largeSlider{
      overflow:hidden;
  }
' . '
  @media screen and (max-width:650px){
    #mainPlayer p {
        font-size: 20px;
        height: 300px;
        padding: 0;
        margin: 0;
      }
      .featureV{
        width: 90%;
        height: 300px;
        border: none;
      }
      .slider1 {
          width:125%;
          margin-bottom: 5px;
      }
      .slider2 {
        width:125%;
        margin-bottom: 5px;
      }
      .slider3,.slider4 {
        width:125%;
        margin-bottom: 5px;
      }
      .slider-heading {
        font-size: 20px;
      }
 
    .arrowL1 {
        font-size: 30px;
    }
    .arrowR1 {
        font-size: 30px;
    }
    .arrowL2 {
        font-size: 30px;
    }
    .arrowR2 {
        font-size: 30px;
    }
    .arrowL3,.arrowL4 {
        font-size: 30px;
    }
    .arrowR3,.arrowL4 {
        font-size: 30px;
    }
}
@media screen and (max-width: 480px){

    .arrowL1 {
        font-size: 15px;
        padding-top: 35px;
	    padding-left: 5px;
    padding-right: 5px;
    }
    .arrowR1 {
        font-size: 15px;
        padding-top: 35px;
	    padding-left: 5px;
    padding-right: 5px;
    }
    .arrowL2 {
        font-size: 15px;
        padding-top: 35px;
	    padding-left: 5px;
    padding-right: 5px;
    }
    .arrowR2 {
        font-size: 15px;
        padding-top: 35px;
	    padding-left: 5px;
    padding-right: 5px;
    }
    .arrowL3,.arrowL4 {
        font-size: 15px;
        padding-top: 35px;
	    padding-left: 5px;
    padding-right: 5px;
    }
    .arrowR3,.arrowR4 {
        font-size: 15px;
        padding-top: 35px;
	    padding-left: 5px;
    padding-right: 5px;
    }
	
}
	' . '
	@media screen and (min-width:501px) and (max-width:600px){
	.nav-inner a{
	padding: 12px 13px;
	}
	.subnav .subnavbtn{
	padding: 12px 13px;
	}
	.subnav-content{
	width: 11.15em;
	}
	}
	@media screen and (min-width:401px) and (max-width:500px){
	.nav-inner a{
	padding: 12px 5px;
    	font-size: 14px;
	}
	.subnav .subnavbtn{
	padding: 12px 5px;
    	font-size: 14px;
	}
	.subnav-content{
	width: 9.50em;
	}
	}
	@media screen and (min-width:301px) and (max-width:400px){
	.nav-inner a{
	padding: 8px 4px;
    	font-size: 11px;
	}
	.subnav .subnavbtn{
	padding: 8px 4px;
    	font-size: 11px;
	}
	.subnav-content{
	width: 7.5em;
	}
	.nav-inner {
	height: 31px;
	}
	}
	@media screen and (max-width:300px){
	.nav-inner a{
	padding: 8px 4px;
    	font-size: 10px;
	}
	.subnav .subnavbtn{
	padding: 8px 4px;
    	font-size: 10px;
	}
	.subnav-content{
	width: 6.9em;
	}
	.nav-inner {
	height: 31px;
	}
	}
	
');
	$__finalCompiled .= '

';
	$__templater->inlineJs('

$(document).ready(function(){
	
	         jQuery(\'a.bla-1\').magnificPopup({
                type: \'iframe\'
            });
	
	
	$(".arrowR1").click(function(){
			var item_width = $(\'.slider1 ul li\').width(); 
			var left_value = item_width * (-1); 
			var left_indent = parseInt($(\'.slider1 ul\').css(\'left\')) - item_width;
			$(\'.slider1 ul\').animate({\'left\' : left_indent}, 100, function () {
			$(\'.slider1 ul li:last\').after($(\'.slider1 ul li:first\'));                  
			$(\'.slider1 ul\').css({\'left\' : left_value,});
	});
	});

	$(".arrowL1").click(function(){
			var item_width = $(\'.slider1 ul li\').width(); 
			var right_value = item_width * (+1); 
			var right_indent = parseInt($(\'.slider1 ul\').css(\'right\')) + item_width;
			$(\'.slider1 ul\').animate({\'right\' : right_indent}, 100, function () {
			$(\'.slider1 ul li:first\').before($(\'.slider1 ul li:last\'));                  
			$(\'.slider1 ul\').css({\'right\' : right_value,});
	});
	});
});	
	
');
	$__finalCompiled .= '


<style>
	.vbr-page-logo
	{
		text-align: center;
		padding: 5px;
	}
</style>

	
   	<div class="main-body">
		
		<div class=\'vbr-page-logo\'>	
			<img src="' . $__templater->func('base_url', array('data/brand_img/logo_images/ron-logo.jpg?t=', ), true) . $__templater->escape($__vars['xf']['time']) . '" alt="Ron-page-logo-image" />
		</div>
		
		';
	if (!$__templater->test($__vars['featureVideo'], 'empty', array())) {
		$__finalCompiled .= '
			<div id="mainPlayer" data-activethumb="0">
				 <p id="promptText">
					<iframe  class="featured-video" src="https://www.youtube.com/embed/' . $__templater->escape($__vars['featureVideo']['video_id']) . '?controls=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				 </p>
			</div>
		';
	}
	$__finalCompiled .= '
			
			
    <div class="main-inner-body">
		
		  <!--LATEST ADDED-->
		';
	if (!$__templater->test($__vars['ronVideos'], 'empty', array())) {
		$__finalCompiled .= '
			<h1 class="slider-heading">Latest Added</h1>

			<div style="position: relative;" class="largeSlider">


				<div class="arrowL1">
					<i class="fa fa-chevron-left"></i>
				</div>
				<div class="arrowR1">
					<i class="fa fa-chevron-right"></i>
				</div>

				<div class="slider1">
					<ul class="slider-list">

						';
		if ($__templater->isTraversable($__vars['ronVideos'])) {
			foreach ($__vars['ronVideos'] AS $__vars['ron']) {
				$__finalCompiled .= '
							<li class="slider-list-item">
								<a class="bla-1" href="' . $__templater->escape($__vars['ron']['video_url']) . '">
									<img class="thumbnail" src="' . $__templater->escape($__templater->method($__vars['ron'], 'getImgUrl', array())) . '">
									<div class="video-title-text">
										' . $__templater->escape($__vars['ron']['title']) . '
									</div>
								</a>
							</li>
						';
			}
		}
		$__finalCompiled .= '

					</ul>
				</div>
			</div>
		';
	}
	$__finalCompiled .= '	
		
	</div>
  </div>';
	return $__finalCompiled;
}
);