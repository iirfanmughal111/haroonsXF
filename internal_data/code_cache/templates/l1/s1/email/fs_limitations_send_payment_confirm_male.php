<?php
// FROM HASH: 5df479769ee5e07f01fe8c2cb412c873
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<style>
	table {
		border-collapse: collapse;
		width: 100%;
	}

	td {
		border: 1px solid #dddddd;
		padding: 8px;
	}

	tr:nth-child(even) {
		background-color: #dddddd;
	}
</style>

<mail:subject>' . 'Receipt for your account upgrade at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '' . '</mail:subject>

<p>' . 'Thank you for purchasing an account upgrade at <a href="' . $__templater->func('link', array('canonical:index', ), true) . '">' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '</a>.' . '</p>
<br/>
<table>
	<tr>
		<td>' . 'Username' . ' </td>
		<td>' . $__templater->escape($__vars['username']) . '</td>
	</tr>
	<tr>
		<td>' . 'Purchased item' . ' </td>
		<td>' . $__templater->escape($__vars['title']) . '</td>
	</tr>
	<tr>
		<td> ' . 'Price' . '</td>
		<td>$' . $__templater->escape($__vars['price']) . '</td>
	</tr>
</table>
<br/>
';
	if ($__templater->method($__vars['xf']['toUser'], 'canUseContactForm', array())) {
		$__finalCompiled .= '
	<p>' . 'Thank you for your purchase. If you have any questions, please <a href="' . $__templater->func('link', array('canonical:misc/contact', ), true) . '">contact us</a>.' . '</p>
	';
	} else {
		$__finalCompiled .= '
	<p>' . 'Thank you for your purchase.' . '</p>
';
	}
	return $__finalCompiled;
}
);