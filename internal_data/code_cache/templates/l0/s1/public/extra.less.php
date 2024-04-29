<?php
// FROM HASH: d41d8cd98f00b204e9800998ecf8427e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
@font-face {
	font-family: \'nebularegular\';
	src: url(\'/styles/new-theme/fonts/nebula-regular-webfont.woff2\') format(\'woff2\'),
		url(\'/styles/new-theme/fonts/nebula-regular-webfont.woff\') format(\'woff\');
	font-weight: normal;
	font-style: normal;
}
@font-face {
	font-family: \'Liberation Sans\';
	src: url(\'/styles/new-theme/fonts/LiberationSans-Bold.woff2\') format(\'woff2\'),
		url(\'/styles/new-theme/fonts/LiberationSans-Bold.woff\') format(\'woff\');
	font-weight: bold;
	font-style: normal;
	font-display: swap;
}
.structItem-title .label{
	display:none;
	
}
.date-thread {
    display: flex;
    color: rgba(175, 175, 175, 1);
	font-size:13px;
	font-family: \'Open Sans\', sans-serif;
	font-weight:400;
	margin-top:10px;
}
.date-thread .dot{

padding:0px 5px;	
}
.thread-comments span {
	padding-left:5px;
}
.thread-grid{
	 display: grid;
 grid-template-columns: repeat(3, 1fr);
gap: 40px 20px;	
}
.thread-grid .structItem {
	display:block;
	    border-top: 0px solid rgba(168, 168, 168, 0.15);
}
.thread-grid .structItem-title{
	font-weight:700;
	font-size:16px;
	color:#fff;
	font-family: \'Open Sans\', sans-serif;
}
.thread-grid .structItem-title a {
	color:#fff;
}
.thread-grid .structItem-cell.structItem-cell--main {
display:block;
	padding:0px;
	padding-top:10px !important;
}
.thread-grid .structItem-cell.structItem-cell--icon,.thread-grid .structItem-parts, .thread-grid .structItem-cell.structItem-cell--meta,.thread-grid .structItem-cell.structItem-cell--latest {
	
	display:none;
}
.block-container{
	background:transparent;
	border:none;
	
}
.num-top{
	font-family: \'nebularegular\';
	font-size:12px;
	color:rgba(118, 118, 118, 1);
	border-bottom: 1px solid rgba(168, 168, 168, 0.15);
	margin-bottom:12px;
}
.structItem-title-cover-perifix .label {
    background: #009dd4;
    border-radius: 0px;
    font-family: \'nebularegular\';
    text-transform: uppercase;
    font-size: 14px;
    line-height: 16px;
    font-weight: 400;
    height: 16px;
    padding: 1px 5px;
    border: none;
    display: block;
    max-width: fit-content;
}
.thread-cover{
	position:relative; 
}
.structItem-title-cover-perifix {
    position: absolute;
    bottom: 11px;
    left: 12px;
}
.inter-view-des {
    color: rgba(255, 255, 255, 1);
    opacity: 0.75;
	font-family: \'Open Sans\', sans-serif;
	font-size:14px;
	font-weight:400;
	margin-top:8px;
}
.inter-view-des p {
	margin:0px;
	line-height:20px;
}

.thread-cover img {
	max-height:240px;
}

.sorting-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
	margin-bottom:66px;
	margin-top:20px;
}
.sorting {
    display: flex;
	align-items: center;
}
.sorting span {
	color:rgba(118, 118, 118, 1);
	font-size:10px;
	font-weight:600;
	font-family: \'Open Sans\', sans-serif;
}
a.sorting-button.active {
    padding: 8px 12px 8px 12px;
    color: #009dd4;
    border-radius: 20px;
    border: 1px solid #009dd4;
    /* height: 25px; */
    display: block;
    line-height: 9px;
}
.sorting-button{
    padding: 8px 12px 8px 12px;
    color:rgba(255, 255, 255, 1);
    border-radius: 20px;
    border:1px solid rgba(64, 64, 64, 1);
    display: block;
    line-height: 9px;
	font-size:12px;
	font-weight:400;
	
}
.sorting-heading-title h1 {
	color:rgba(255, 255, 255, 1);
	font-size:32px;
	line-height:25px;
	 font-family: \'nebularegular\';
    text-transform: uppercase;
	font-weight:400;
	margin:0px;
}
.sorting ul {
    margin: 0px;
    display: inline-block;
	    padding-left: 12px;
}
.sorting ul li {
  display: inline-block;
  margin-inline: 4px;	
}
@media (max-width:992px) {
	.thread-grid {
	 grid-template-columns: repeat(2, 1fr);	
	}
	
}
@media (max-width:650px) {
	.thread-grid {
	 grid-template-columns: repeat(1, 1fr);	
	}
	
}';
	return $__finalCompiled;
}
);