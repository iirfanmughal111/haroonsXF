<?php
// FROM HASH: 415797cb6085ea24e9f5fe37959ed1e8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['filters']['tvgenre']) {
		$__finalCompiled .= '
	<li><a href="' . $__templater->func('link', array('forums', $__vars['forum'], $__templater->filter($__vars['filters'], array(array('replace', array('tvgenre', null, )),), false), ), true) . '"
		class="filterBar-filterToggle" data-xf-init="tooltip" title="' . 'Remove this filter' . '">
		<span class="filterBar-filterToggle-label">' . 'Genre' . ':</span>
		' . $__templater->escape($__vars['filters']['tvgenre']) . '</a></li>
';
	}
	$__finalCompiled .= '
';
	if ($__vars['filters']['tvcast']) {
		$__finalCompiled .= '
	<li><a href="' . $__templater->func('link', array('forums', $__vars['forum'], $__templater->filter($__vars['filters'], array(array('replace', array('tvcast', null, )),), false), ), true) . '"
		class="filterBar-filterToggle" data-xf-init="tooltip" title="' . 'Remove this filter' . '">
		<span class="filterBar-filterToggle-label">' . 'Cast' . ':</span>
		' . $__templater->escape($__vars['filters']['tvcast']) . '</a></li>
';
	}
	$__finalCompiled .= '
';
	if ($__vars['filters']['creator']) {
		$__finalCompiled .= '
	<li><a href="' . $__templater->func('link', array('forums', $__vars['forum'], $__templater->filter($__vars['filters'], array(array('replace', array('creator', null, )),), false), ), true) . '"
		class="filterBar-filterToggle" data-xf-init="tooltip" title="' . 'Remove this filter' . '">
		<span class="filterBar-filterToggle-label">' . 'Creator' . ':</span>
		' . $__templater->escape($__vars['filters']['creator']) . '</a></li>
';
	}
	$__finalCompiled .= '
';
	if ($__vars['filters']['tv_title']) {
		$__finalCompiled .= '
	<li><a href="' . $__templater->func('link', array('forums', $__vars['forum'], $__templater->filter($__vars['filters'], array(array('replace', array('tv_title', null, )),), false), ), true) . '"
		class="filterBar-filterToggle" data-xf-init="tooltip" title="' . 'Remove this filter' . '">
		<span class="filterBar-filterToggle-label">' . 'TV show title' . ':</span>
		' . $__templater->escape($__vars['filters']['tv_title']) . '</a></li>
';
	}
	return $__finalCompiled;
}
);