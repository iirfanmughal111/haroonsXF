<?php
// FROM HASH: d1572bca7e93f8b85c223609ac492cec
return array(
'macros' => array('limitBar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'number' => '10',
		'percentage' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<style>
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

</style>
	<div class="side">
	
	</div>
	<div class="middle" >
		<div class="bar-container">
			<div class="bar" style="width:' . $__templater->escape($__vars['percentage']) . '%;"></div>
		</div>
	</div>
	<div class="side right">
		<div>' . $__templater->escape($__vars['percentage']) . ' %</div>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);