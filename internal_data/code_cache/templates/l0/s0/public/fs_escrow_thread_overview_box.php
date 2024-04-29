<?php
// FROM HASH: ac655ed8f03621143ddc158ccb64fed6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-body block-row">
		
		<dl  class="pairs pairs--justified ">
				<dt>
				' . 'Status' . '
					</dt>
				<dd>
					' . $__templater->callMacro('fs_escrow_list_macro', 'status', array(
		'status' => $__vars['thread']['Escrow']['escrow_status'],
	), $__vars) . '
				</dd>
			</dl>
		
		<dl  class="pairs pairs--justified ">
				<dt>
				' . 'Amount' . '
					</dt>
				<dd>
					' . '$' . $__templater->escape($__vars['thread']['Escrow']['escrow_amount']) . '
				</dd>
			</dl>
		
		<dl  class="pairs pairs--justified ">
				<dt>
				' . 'Starter' . '
					</dt>
				<dd>
					' . $__templater->escape($__vars['thread']['User']['username']) . '
				</dd>
			</dl>
		
		<dl  class="pairs pairs--justified ">
				<dt>
				' . 'Mentioned' . '
					</dt>
				<dd>
					' . $__templater->escape($__vars['thread']['Escrow']['User']['username']) . '
				</dd>
			</dl>
		
	</div>';
	return $__finalCompiled;
}
);