<?php
// FROM HASH: e816bc9df951224e8cdfef5f8c34c6dd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['canTHUIChangeName']) {
		$__finalCompiled .= '
	' . $__templater->formTextBoxRow(array(
			'name' => 'user[username]',
			'value' => $__vars['xf']['visitor']['username'],
			'maxlength' => $__templater->func('max_length', array($__vars['xf']['visitor'], 'user_name', ), false),
		), array(
			'label' => 'Username',
			'explain' => 'Allows you to change your username across the site.',
		)) . '
';
	} else {
		$__finalCompiled .= '
	';
		if ($__vars['nextTHUIChangeOn']) {
			$__finalCompiled .= '
		<dl class="formRow formRow--input">
			<dt>
				<div class="formRow-labelWrapper">
					<label class="formRow-label">' . 'Username' . '</label></div>
			</dt>
			<dd>
				<div>
					' . 'Next change on ' . $__templater->func('date_time', array($__vars['nextTHUIChangeOn'], ), true) . '' . '
				</div>
				<div class="formRow-explain">' . 'Allows you to change your username across the site.' . '</div>
			</dd>
		</dl>
	';
		}
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);