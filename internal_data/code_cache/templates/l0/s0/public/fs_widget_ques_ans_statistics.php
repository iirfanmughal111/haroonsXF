<?php
// FROM HASH: 4f8140932d38c985f375c38b873d035e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
	<div class="block-container">
		<h3 class="block-minorHeader">' . $__templater->escape($__vars['title']) . '</h3>
		<div class="block-body block-row">
			<dl class="pairs pairs--justified">
				<dt>' . 'Question Expert' . '</dt>
				<dd>' . $__templater->filter($__vars['questionAnswerCount']['question'], array(array('number', array()),), true) . '</dd>
			</dl>

			<dl class="pairs pairs--justified">
				<dt>' . 'Answer Expert' . '</dt>
				<dd>' . $__templater->filter($__vars['questionAnswerCount']['ans'], array(array('number', array()),), true) . '</dd>
			</dl>

		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);