<?php
// FROM HASH: e2ebc927de748c6bcd90d6e0b964ac55
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '@media screen and (max-width: 642px) {

	 .borderpage{
	
		height:140px;
		width: 100px;
     }

	.fas{
			font-size: 5em;
		}	
	
	.tabs--standalone .tabs-tab {
      
		padding: 11px 21px 3px;
   
         }
	
	.grid-page-item {
		
 	display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-gap: 0px 9px;
    padding: 0px 0px;
		
     }
	
   .grid-page-all{
		
	display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-gap: 0px 9px;
    padding: 0px 0px;
		
	}
	
	.pageimage{
		width:110px;
		height:70px;
	}
	
		
	}

@media screen and (min-width:640px) {
 
   .borderpage{
	
	height: 150px;
    width: 150px;
	   
    }
	.grid-page-item {
		
 	display: grid;
    grid-template-columns: repeat(6, 1fr);
    grid-gap: 0px 9px;
    padding: 0px 0px;
		
     }
	
  .grid-page-all{
		
	display: grid;
    grid-template-columns: repeat(7, 1fr);
    grid-gap: 0px 9px;
    padding: 0px 0px;
		
	}
	
	.pageimage{
		width:110px;
		height:60px;
	}
	
			
	
   }



.innerpage{
	
		list-style: none;
}

.bh-title {
	border-left:2 solid blue !important;
}
.brandHub {
    font-size: 13px;
	padding: 10px 20px;
}

.bh_item {
    width: 100%;
    /* font-family: CoreSans,Arial,sans-serif; */
    letter-spacing: .5px;
    padding: 12px 0 10px!important;
	line-height: 18px;
	list-style: none;
}
.bh_a:hover
{
	color:red !important;
	text-decoration: none ;
}

.bh_a {
	color:black;
}

.grid-list {
 	display: grid;
    grid-template-columns: repeat(5, 1fr);
    grid-gap: 0px 25px;
    padding: 0px 10px;
}
.highestRated_grid-list{
	display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 0px 25px;
    padding: 0px 10px;
}
.clearfix{
	clear: both;
	margin-top: 25px;
}
.itemPageNav{
	
}
[data-template="bh_item_detail"] .p-body-main--withSidebar &
    {  
		
    }
[data-template="bh_page_detail"] .p-body-main--withSidebar &
    {
		
    }
/* title rating */

.title-rating {
    position: relative;
    top: -58px;
    right: -196px;
    width: 137px;
}
@media screen and (max-width: 642px){
		.title-rating {
			position: relative;
			top: -70px;
			right: -170px;
			width: 129px;
		}
}
@media screen and (max-width: 414px){
	.title-rating {
    position: relative;
    top: -40px;
    right:2px;
    width: 129px;
}
	}
/* white three button */

.white-button {
    margin-right: 5px;
    background: white;
    color: black;
    border: white;
}
#white-button{
	    margin-right: 5px;
    background: white;
    color: black;
    border: white;
}
.tab-white-button{
	
	float:left;
}
@media screen and (max-width: 642px){
		.tabs--standalone .tabs-tab {
			padding: 8px 5px 3px;
		}
}
/*
@m-sticky-top: 48px;
@media (min-width: (@xf-responsiveWide + 1px)) {
    .p-body-main--withSidebar {
        .p-body-sideNav,
        .p-body-sidebar {
            flex: 0 0 auto;
            display: block;
        }
        @supports ((position: sticky) or (position: -webkit-sticky)) {
            .p-body-sidebar {
                position: -webkit-sticky;
                position: sticky;
                top: 10px;
                & when (@xf-publicNavSticky =primary) or (@xf-publicNavSticky =all) {
                    top: @m-sticky-top * 1px + 10px;
                }
            }
        }
    }
}*/


.borderpage{
	
	border: 2px solid orange;
    padding: 14px;
    text-align: center;
    border-radius: 5px;

}

.bh_center{
	display: flex;
	align-items: center;
	justify-content: center;
	padding:auto;
}

//rating bar --------------------------------------------------------


.checked{
	color: #f9c479;
}

/* Three column layout */
.side {
  float: left;
  width: 8%;
  margin-top: 5px;
}

.middle {
  float: left;
  width: 77%;
  margin-top: 10px;
}

/* Place text to the right */
.right {
  text-align: right;
}

/* Clear floats after the columns 
.row:after {
  content: "";
  display: table;
  clear: both;
}
*/

/* The bar container */
.bar-container {
  width: 100%;
  background-color: #f1f1f1;
  text-align: center;
  color: white;
	border-radius: 5px;
}
.reviewStarsDiv{
	float:left;
}
.ratingBarsDiv{
	width: 60%;
	float:right;
	font-size: smaller;
}

.bar {height: 10px; background-color: #f9c479; border-radius: 5px;}

/* Responsive layout - make the columns stack on top of each other instead of next to each other */
@media (max-width: 400px) {
  .side, .middle {
    width: 100%;
  }
  /* Hide the right column on small screens */
  .right {
    display: none;
  }
}

//ad -----------------------------------------------------------


.sideBar_ad{
	height:250px;
	display: flex;
	align-items: center;
	justify-content: center;
}

.center_ad{
	height:150px;
	display: flex;
	align-items: center;
	justify-content: center;
}


//************************************************item next and item preivew
.media
{
	position: relative;
	margin-bottom: 10px;
}


@_buttonWidth: 30px;
@_buttonHeight: 50px;


.media-button
{
	position: absolute;
	top: ~"calc(50% - "(@_buttonHeight / 2) + 2~")";
	z-index: @zIndex-1;

	width: @_buttonWidth;
	height: @_buttonHeight;

	background: fade(mix(@xf-paletteNeutral2, @xf-paletteNeutral3), 70%);
	border-radius: @xf-borderRadiusMedium;

	opacity: 0.2;
	.m-transition(opacity);

	cursor: pointer;

	.has-touchevents &,
	.media:hover &
	{
		opacity: 0.6;
	}

	&&:hover
	{
		text-decoration: none;
		opacity: 1;
	}

	.media-button-icon
	{
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);

		color: #FFF;
		.m-textOutline(white, black);

		.m-faBase();
		font-size: 1.75em;
	}

	&.media-button--next
	{
		right: 5px;

		.media-button-icon
		{
			&:before
			{
				.m-faContent(@fa-var-chevron-right, .71em, ltr);
				.m-faContent(@fa-var-chevron-left, .71em, rtl);
			}
		}
	}

	&.media-button--prev
	{
		left: 5px;

		.media-button-icon
		{
			&:before
			{
				.m-faContent(@fa-var-chevron-left, .71em, ltr);
				.m-faContent(@fa-var-chevron-right, .71em, rtl);
			}
		}
	}
}

//*************************';
	return $__finalCompiled;
}
);