<?php
// FROM HASH: 7037bfa864bece2ecfb8e59a12baf245
return array(
'macros' => array('scheduled_input' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'class' => '',
		'value' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('bssp_schedule.less');
	$__finalCompiled .= '
	';
	$__templater->includeCss('bssp_bootstrap_datetimepicker.less');
	$__finalCompiled .= '
	
	';
	$__templater->includeJs(array(
		'src' => 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment-with-locales.min.js',
	));
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'bs/scheduled_posting/bootstrap.min.js',
	));
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js',
	));
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'bs/scheduled_posting/posting.js',
		'min' => '1',
	));
	$__finalCompiled .= '

	<div class="schedule-input js-scheduleInput' . ($__vars['class'] ? (' ' . $__templater->escape($__vars['class'])) : '') . ($__vars['value'] ? ' scheduled' : '') . '" 
		 data-xf-init="schedule-input"
		 data-min-date="' . ($__vars['xf']['time'] + 60) . '"
		 data-locale="' . $__templater->filter($__vars['xf']['language']['language_code'], array(array('substr', array(0, 2, )),), true) . '"
		 data-trigger-format="' . 'Do MMMM YYYY in HH:mm' . '">
		
		<span class="schedule-trigger js-scheduleTrigger">' . ($__vars['value'] ? $__templater->func('date', array($__vars['value'], 'd F Y H:i', ), true) : 'Now') . '</span>
		<span class="schedule-reset js-scheduleReset"><i class="icon"></i></span>
		<input type="checkbox" name="scheduled[is]" class="js-scheduleCheckbox"' . ($__vars['value'] ? ' checked="checked"' : '') . ' />
		' . $__templater->formTextBox(array(
		'name' => 'scheduled[posting_date]',
		'class' => 'js-scheduleDate',
		'value' => ($__vars['value'] ? $__templater->func('date', array($__vars['value'], 'd.m.Y H:i', ), false) : ''),
	)) . '
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