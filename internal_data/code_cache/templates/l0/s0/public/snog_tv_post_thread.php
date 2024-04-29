<?php
// FROM HASH: b86e515e65756af0a58a3991860e3ad0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="p-body-header">
	<div class="p-title">
		<h1 class="p-title-value"></h1>
		<div class="p-title-pageAction">
			' . $__templater->button('
				' . 'Post thread' . '
			', array(
		'href' => $__templater->func('link', array('forums/post-thread', $__vars['forum'], ), false),
		'class' => 'button--cta',
		'icon' => 'write',
	), '', array(
	)) . '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);