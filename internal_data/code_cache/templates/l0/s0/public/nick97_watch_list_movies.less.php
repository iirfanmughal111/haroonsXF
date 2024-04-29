<?php
// FROM HASH: 9b37caf4bb3dbe428bd350169b5c88f0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.p-body-section{
	margin-bottom:0 !important;
}
#container
{

	height: auto;
	overflow: hidden;
	margin: 0 auto;
	position: relative;
	margin-bottom:20px;
}
#list-container
{
	overflow: hidden;
	width: 100%;
	float: left;
}
.list
{

	min-width: 3400px;
	float: left;
	display: flex;
}

#arrowL::before,#arrowR::before{
	font-family: "Font Awesome 5 Pro";
	content: "\\f053";
	background: #fff;
	border-radius: 50%;

	color: #2196f3;
	display: block;
	font-size: 20px;
	font-weight: bold;
	height: 40px;
	line-height: 40px;
	opacity: 1;
	width: 40px;
	text-align: center;
}



#arrowR::before {
	font-family: "Font Awesome 5 Pro";
	content: "\\f054";
}	


#arrowR
{
	background: transparent;
	width: 40px;
	height: 40px;
	border-radius: 50%;
	float: right;
	cursor: pointer;
	top: 126px;
	text-align: center;
	font-family: Arial;
	font-size: 0px;
	color: transparent;
	padding:2px 2px;
	position: absolute;
	z-index: 100;
	right: 20px;

	display: block;
	line-height: 0px;
	font-size: 0px;
	padding: 0;
	border: none;
	outline: none;
}
#arrowL
{
	top: 145px;
	left: 9px;
	background: transparent;
	width: 40px;
	height: 40px;
	float: left;
	cursor: pointer;
	text-align: center;
	font-family: Arial;
	color: transparent;
	position: absolute;
	z-index: 100;
	border-radius: 50%;
	cursor: pointer;
	display: block;
	line-height: 0px;
	font-size: 0px;
	-webkit-transform: translate(0, -50%);
	padding: 0;
	border: none;
	outline: none;
}
.item
{
	margin: 0 10px 0 0;
	float: left;
	position: relative;
	text-align: center;
	font-family: Arial;
	font-size: 20px;
	color: White;
	max-width: 185px;
	max-height: 278px;
	min-width: 185px;
    min-height: 278px;
}

div.item iframe{
	width: 100%;
	height: 100%;
}
div.p-body-section div.p-body-section-header span.p-body-section-icon i.fa-gamepad-alt.screen-icon:before {
	content: "\\f108" !important;
}


/* MEDIA QUERIES.............
MEDIA QUERIES.............
MEDIA QUERIES............. */

@media only screen and (max-width: 600px) {
	#container{
		width: 100%;
	}
	.item{
		margin: 0 15px 0 15px;
	}
}



/* Container to hold the two boxes */
.container {
	display: flex; /* Use flexbox */
	justify-content: space-between; /* Add space between the two boxes */
	flex-wrap: wrap; /* Allow wrapping to the next line on smaller screens */
}

/* Styles for the individual boxes */
.box {
	display: block;
	box-sizing: border-box; /* Include padding and border in the box\'s total width */
	margin-bottom: 5px; /* Add some space between the boxes */
}

.right_watch_list{
	cursor: pointer;
}

.right_watch_list:hover{
	color: blue;
}';
	return $__finalCompiled;
}
);