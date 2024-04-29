<?php
// FROM HASH: 4d658e150e89d0706d27483f49f0515f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__vars['scheduled']['id']) {
		$__compilerTemp1 .= ' ' . 'Edit Scheduled' . ' ';
		$__templater->breadcrumb($__templater->preEscaped('Edit Scheduled'), '#', array(
		));
		$__compilerTemp1 .= ' ';
	} else {
		$__compilerTemp1 .= ' ' . 'Add Scheduled' . ' ';
		$__templater->breadcrumb($__templater->preEscaped('Add Scheduled'), '#', array(
		));
		$__compilerTemp1 .= ' ';
	}
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('
  ' . $__compilerTemp1 . '
');
	$__finalCompiled .= '

' . $__templater->form('
  <div class="block-container">
    <div class="block-body">
      ' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['shedule']['title'],
		'autosize' => 'true',
		'row' => '5',
		'required' => 'true',
	), array(
		'label' => 'Title',
	)) . '


      ' . $__templater->formUploadRow(array(
		'name' => 'message_file',
		'accept' => '.xls,.xlsx',
		'required' => ($__templater->method($__vars['shedule'], 'isInsert', array()) ? true : false),
	), array(
		'label' => 'Message File',
	)) . '

      ' . $__templater->formUploadRow(array(
		'name' => 'location_file',
		'accept' => '.xls,.xlsx',
		'required' => ($__templater->method($__vars['shedule'], 'isInsert', array()) ? true : false),
	), array(
		'label' => 'Location File',
	)) . '

  

      ' . $__templater->formRow('<span class="inputGroup-text">
          ' . 'Scheduled Window' . '
        </span>', array(
	)) . '

   
    ' . $__templater->formRow('<span class="inputGroup-text">
         ' . $__templater->func('date_time', array($__vars['xf']['time'], ), true) . '
       </span>
 
   ', array(
		'label' => 'Server Time',
	)) . '

      ' . $__templater->formRow('
        <div class="inputGroup">
          ' . $__templater->formDateInput(array(
		'name' => 'schedule_start',
		'value' => $__vars['shedule']['schedule_start'],
	)) . '
          <span class="inputGroup-text"> ' . 'Time' . $__vars['xf']['language']['label_separator'] . ' </span>
          <span class="inputGroup" dir="ltr">
          		' . $__templater->formTextBox(array(
		'name' => 'sch_starttime',
		'class' => 'input--date time start',
		'required' => 'true',
		'type' => 'time',
		'value' => ($__templater->method($__vars['shedule'], 'getScheduleStart', array()) ? $__templater->method($__vars['shedule'], 'getScheduleStart', array()) : ''),
		'data-xf-init' => 'time-picker',
		'data-moment' => $__vars['timeFormat'],
		'data-format' => $__vars['xf']['language']['time_format'],
	)) . '
          </span>
        </div>
      ', array(
		'label' => 'Start Date',
	)) . '

      ' . $__templater->formRow('
        <div class="inputGroup">
          ' . $__templater->formDateInput(array(
		'name' => 'schedule_end',
		'value' => $__vars['shedule']['schedule_end'],
	)) . '
          <span class="inputGroup-text"> ' . 'Time' . $__vars['xf']['language']['label_separator'] . ' </span>
          <span class="inputGroup" dir="ltr">
           ' . $__templater->formTextBox(array(
		'name' => 'sch_endtime',
		'class' => 'input--date time start',
		'required' => 'true',
		'type' => 'time',
		'value' => ($__templater->method($__vars['shedule'], 'getScheduleEnd', array()) ? $__templater->method($__vars['shedule'], 'getScheduleEnd', array()) : ''),
		'data-xf-init' => 'time-picker',
		'data-moment' => $__vars['timeFormat'],
		'data-format' => $__vars['xf']['language']['time_format'],
	)) . '
          </span>
        </div>
      ', array(
		'label' => 'End Date',
	)) . '

      ' . $__templater->formRow('', array(
	)) . '

      ' . $__templater->formRow('
        <div class="inputGroup">
          ' . $__templater->formDateInput(array(
		'name' => 'posting_start',
		'value' => $__vars['shedule']['posting_start'],
	)) . '
          <span class="inputGroup-text"> ' . 'Time' . $__vars['xf']['language']['label_separator'] . ' </span>
          <span class="inputGroup" dir="ltr">
            ' . $__templater->formTextBox(array(
		'name' => 'sch_str_time',
		'class' => 'input--date time start',
		'required' => 'true',
		'type' => 'time',
		'value' => ($__templater->method($__vars['shedule'], 'getPostStarting', array()) ? $__templater->method($__vars['shedule'], 'getPostStarting', array()) : ''),
		'data-xf-init' => 'time-picker',
		'data-moment' => $__vars['timeFormat'],
		'data-format' => $__vars['xf']['language']['time_format'],
	)) . '
          </span>
        </div>
      ', array(
		'label' => 'Post Start Date',
	)) . '
    </div>

    ' . $__templater->formSubmitRow(array(
		'submit' => 'save',
		'fa' => 'fa-save',
	), array(
	)) . '
  </div>
', array(
		'action' => $__templater->func('link', array('schedule/save', $__vars['shedule'], ), false),
		'class' => 'block',
		'ajax' => '1',
	));
	return $__finalCompiled;
}
);