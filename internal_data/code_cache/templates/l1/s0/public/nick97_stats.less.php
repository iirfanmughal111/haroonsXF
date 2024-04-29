<?php
// FROM HASH: 7a6804013e3573ac2b13bae71a8c3f9c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.stats-p-body-section{
				margin-bottom:0 !important;
			}

			/* Container to hold the two boxes */
			.statsContainer {
				display: flex; /* Use flexbox */
				justify-content: space-between; /* Add space between the two boxes */
				flex-wrap: wrap; /* Allow wrapping to the next line on smaller screens */
			}

			/* Styles for the individual boxes */
			.statsBox {
				display: block;
				box-sizing: border-box; /* Include padding and border in the box\'s total width */
				margin-bottom: 5px; /* Add some space between the boxes */
			}

			.myContainer {
				display: flex;
				flex-wrap: wrap;
				justify-content: space-between;
				margin: 0 auto;
				width: 100%;
			}

			.myBox {
				background-color: #eee;
				border: 1px solid #ddd; /* Added border definition */
				border-radius: 4px;
				width: 24%;
				margin-bottom: 10px;
				color: #000;
			}

			.myBox-heading {
				font-size: 18px;
				font-weight: bold;
				margin-bottom: 10px;
				text-align: center;
				border-bottom: 1px solid #ddd;
				padding-bottom: 10px;
			}

			.myBox-body{
				text-align: center;
			}

			@media screen and (max-width: 768px) {
				.myBox {
					width: 45%;
				}
			}

			@media screen and (max-width: 480px) {
				.myBox {
					width: 100%;
				}
			}

			.myContainer1 {
				display: flex;
				flex-wrap: wrap;
				justify-content: space-around;
				margin: 0 auto;
				width: 100%;
			}

			.right_statsvideo{
				cursor: pointer;
			}

			.right_statsvideo:hover{
				color: blue;
			}';
	return $__finalCompiled;
}
);