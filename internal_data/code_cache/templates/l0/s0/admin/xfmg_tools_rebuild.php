<?php
// FROM HASH: 00a0ff378c8fd63a9eeeb45b79f7afb0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild media items',
		'job' => 'XFMG:MediaItem',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild media thumbnails',
		'job' => 'XFMG:MediaThumb',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Update media watermarks',
		'job' => 'XFMG:UpdateWatermark',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild albums',
		'job' => 'XFMG:Album',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild album thumbnails',
		'job' => 'XFMG:AlbumThumb',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild categories',
		'job' => 'XFMG:Category',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild user counts',
		'job' => 'XFMG:UserCount',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild user media quotas',
		'job' => 'XFMG:UserMediaQuota',
	), $__vars) . '
';
	return $__finalCompiled;
}
);