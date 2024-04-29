<?php
// FROM HASH: ba961905d22a50e290f5726888c0c6ef
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Create');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['record'], 'empty', array())) {
		$__compilerTemp1 .= '
						' . $__templater->formInfoRow('
							<img src="' . $__templater->escape($__templater->method($__vars['record'], 'getImgUrl', array(true, 'icon', ))) . '" style="width:80px;height:60px" >
						', array(
			'rowtype' => 'confirm',
		)) . '
					';
	}
	$__compilerTemp2 = '';
	if (!$__templater->test($__vars['record'], 'empty', array())) {
		$__compilerTemp2 .= '
						' . $__templater->formInfoRow('
							<img src="' . $__templater->escape($__templater->method($__vars['record'], 'getImgUrl', array(true, 'header', ))) . '" style="width:80px;height:60px" >
						', array(
			'rowtype' => 'confirm',
		)) . '
					';
	}
	$__compilerTemp3 = '';
	if ($__templater->isTraversable($__vars['record']['tourn_prizes'])) {
		foreach ($__vars['record']['tourn_prizes'] AS $__vars['key'] => $__vars['value']) {
			$__compilerTemp3 .= '
							
							<div class="inputGroup">
								
								' . $__templater->formTextBox(array(
				'name' => 'tourn_parto[]',
				'value' => $__vars['key'],
				'placeholder' => 'First One',
				'size' => '24',
				'maxlength' => '25',
				'dir' => 'ltr',
			)) . '
								
								<span class="inputGroup-splitter"></span>
								
								' . $__templater->formTextBox(array(
				'name' => 'tourn_partt[]',
				'value' => $__vars['value'],
				'placeholder' => 'Last One',
				'size' => '24',
			)) . '
							</div>
						';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			
				' . $__templater->formTextBoxRow(array(
		'name' => 'tourn_title',
		'autofocus' => 'autofocus',
		'maxlength' => $__templater->func('max_length', array($__vars['xf']['visitor'], 'title', ), false),
		'value' => $__vars['record']['tourn_title'],
		'required' => 'required',
	), array(
		'label' => 'Title',
		'hint' => 'Required',
	)) . '
					
				' . $__templater->formRow('
						<div class="inputGroup">
			
					' . $__templater->formDateInput(array(
		'name' => 'tourn_startdate',
		'class' => 'date start',
		'value' => ($__vars['record'] ? $__templater->method($__vars['record'], 'getStartDate', array()) : ''),
		'required' => 'true',
	)) . '
							
								<span class="inputGroup-splitter"></span>
							
					' . $__templater->formTextBox(array(
		'name' => 'tourn_starttime',
		'class' => 'input--date time start',
		'required' => 'true',
		'type' => 'time',
		'value' => ($__vars['record'] ? $__templater->method($__vars['record'], 'getStartTime', array()) : ''),
		'data-xf-init' => 'time-picker',
		'data-moment' => $__vars['timeFormat'],
		'data-format' => $__vars['xf']['language']['time_format'],
	)) . '
					
						</div>
				', array(
		'rowtype' => 'input',
		'label' => 'Start date',
	)) . '
			
				' . $__templater->formRow('
					<div class="inputGroup">
			
					' . $__templater->formDateInput(array(
		'name' => 'tourn_enddate',
		'class' => 'date end',
		'value' => ($__vars['record'] ? $__templater->method($__vars['record'], 'getEndDate', array()) : ''),
		'required' => 'true',
	)) . '
							
							<span class="inputGroup-splitter"></span>
					' . $__templater->formTextBox(array(
		'name' => 'tourn_endtime',
		'class' => 'input--date time start',
		'required' => 'true',
		'type' => 'time',
		'value' => ($__vars['record'] ? $__templater->method($__vars['record'], 'getEndTime', array()) : ''),
		'data-xf-init' => 'time-picker',
		'data-moment' => $__vars['timeFormat'],
		'data-format' => $__vars['xf']['language']['time_format'],
	)) . '
					
					</div>
				', array(
		'rowtype' => 'input',
		'label' => 'End date',
	)) . '
			
				' . $__templater->formUploadRow(array(
		'name' => 'icon',
		'accept' => '.gif,.jpeg,.jpg,.jpe,.png',
	), array(
		'label' => 'Icon',
		'hint' => 'Required',
		'explain' => 'Upload Icon Image',
	)) . '
			
					' . $__compilerTemp1 . '
			
				' . $__templater->formUploadRow(array(
		'name' => 'header',
		'accept' => '.gif,.jpeg,.jpg,.jpe,.png',
	), array(
		'label' => 'Header',
		'hint' => 'Required',
		'explain' => 'Upload Header Image',
	)) . '
			
					' . $__compilerTemp2 . '
			
				' . $__templater->formTextBoxRow(array(
		'name' => 'tourn_main_price',
		'maxlength' => $__templater->func('max_length', array($__vars['xf']['visitor'], 'tourn_main_price', ), false),
		'value' => $__vars['record']['tourn_main_price'],
		'required' => 'required',
	), array(
		'label' => 'Main Price',
		'explain' => 'Kindly add main price',
		'hint' => 'Required',
	)) . '
			
				<hr class="formRowSep" />
			
				' . $__templater->formTextAreaRow(array(
		'name' => 'tourn_desc',
		'rows' => '3',
		'autosize' => 'true',
		'required' => 'true',
		'value' => $__vars['record']['tourn_desc'],
	), array(
		'label' => 'Description',
		'hint' => 'Required',
		'explain' => 'Kindly describe in details',
	)) . '			
		</div>
		
		' . $__templater->formRow('
				 
				 <div class="inputGroup-container" data-xf-init="list-sorter" data-drag-handle=".dragHandle">
					 
						' . $__compilerTemp3 . '
					 
					 <div class="inputGroup is-undraggable js-blockDragafter" data-xf-init="field-adder"
							data-remove-class="is-undraggable js-blockDragafter">
						 
							' . $__templater->formTextBox(array(
		'name' => 'tourn_parto[]',
		'placeholder' => 'First One',
		'size' => '24',
		'maxlength' => '25',
		'data-i' => '0',
		'dir' => 'ltr',
	)) . '
						 
							<span class="inputGroup-splitter"></span>
						 
							' . $__templater->formTextBox(array(
		'name' => 'tourn_partt[]',
		'placeholder' => 'Last One',
		'size' => '24',
		'data-i' => '0',
	)) . '
					</div>
					 
			 	</div>	
				', array(
		'rowtype' => 'input',
		'label' => 'Prizes',
	)) . '
		
		' . $__templater->formSubmitRow(array(
		'submit' => '',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('tourn/save', $__vars['record'], ), false),
		'class' => 'block',
		'data-force-flash-message' => 'true',
		'enctype' => 'multipart/form-data',
	));
	return $__finalCompiled;
}
);