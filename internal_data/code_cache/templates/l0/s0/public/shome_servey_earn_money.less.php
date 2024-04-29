<?php
// FROM HASH: 6c353712a9eaaa8fa65e3c5c03ad7cca
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '#waySection {
  background: #2d2118 url(https://beersurveys.com/Scripts/default/assets/css/images/main_back.jpg) no-repeat left top;
  background-size: cover;
  padding-top: 60px;
}

#waySection .way-title {
  font-size: 48px;
  font-family: "Oswald", sans-serif;
  text-transform: uppercase;
  font-weight: 300;
}

#waySection .section-author .advice h3 {
  color: white;
}
#waySection h3 {
  font-size: 30px;
  color: #ffc13c;
}

.way-content{
  padding: 100px 15px 65px;
}

.way-content .way-title {
  text-align: center;
  position: relative;
  margin-bottom: 30px;
  padding-bottom: 15px;
  margin-top: 0;
  font-size: 48px;
  font-family: "Oswald", sans-serif;
  text-transform: uppercase;
  color: mix(@xf-textColor, @xf-textColorMuted);
  font-weight: 300;
}

.way-content .way-title {
  text-align: center;
  position: relative;
  margin-bottom: 30px;
  padding-bottom: 15px;
  margin-top: 0;
}

.way-content .way-title:after {
  content: "";
  display: block;
  position: absolute;
  width: 40px;
  background: #242a30;
  height: 2px;
  bottom: 0;
  left: 50%;
  margin-left: -20px;
}

.way-container {
  padding-right: 15px;
  padding-left: 15px;
  margin-right: auto;
  margin-left: auto;
}

.block:after,
.block:before,
.way-container:before {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  display: table;
  content: " ";
}

h2 {
  font-weight: 500;
  margin-top: 0;
  margin-bottom: 15px;
  line-height: 1.25;
  display: block;
  font-size: 1.5em;
  margin-block-start: 0.83em;
  margin-block-end: 0.83em;
  margin-inline-start: 0px;
  margin-inline-end: 0px;
}

.wayrow {
  margin: 0 -10px;
  margin-right: -15px;
  margin-left: -15px;
}

.wayrow:before,
.wayrow:after {
  display: table;
  content: " ";
}

.wayrow:after {
  clear: both;
}

.col > [class*="col-"] {
  padding: 0 10px;
}

.waycol-md-3,
.waycol-sm-12 {
  position: relative;
  min-height: 1px;
  padding-right: 10px;
  padding-left: 10px;
	 width: 20%;
}

div {
  display: block;
}

.ways-block {
    border-radius: 4px;
    box-shadow: 0 0px 8px 0 rgba(0,0,0,.4);
    margin: 10px;
    text-align: center;
    padding: 20px;
}

.ways-block h2 {
    color: mix(@xf-textColor, @xf-textColorMuted);
    font-family: \'Oswald\', sans-serif;
    font-size: 12px;
    text-transform: uppercase;
}


.ways-block img {
    width: auto;
	height: 100px;
}

@media (min-width: 1200px) {
    .way-container{
    width: 1170px;
  }
}
@media (min-width: 992px) {
    .way-container {
    width: 970px;
  }
  .waycol-md-3 {
    width: 20%;
    float: left;
  }
	.wayrow{
		width: 106%;
	}
}
@media only screen and (max-width: 480px) {
  .waycol-md-3 {
    width: 100%;
    
  }
}
@media (max-width: 768px) {
  #waySection .way-title {
    font-size: 22px;
  }
	.wayrow {
    width: 90%;
    margin-left: 17px;
}
	.waycol-md-3 {
    width: 100%;
    display: -webkit-inline-box;
    
}
  .display-none {
    display: none;
  }
}';
	return $__finalCompiled;
}
);