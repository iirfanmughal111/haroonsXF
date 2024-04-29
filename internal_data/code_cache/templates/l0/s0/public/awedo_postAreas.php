<?php
// FROM HASH: 30423aa0f699403bd77a0aa311f50650
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('' . $__templater->escape($__vars['user']['username']) . '\'s post areas');
	$__finalCompiled .= '

';
	$__templater->includeCss('awedo_post_areas_tab_content.css');
	$__finalCompiled .= '

<div class="block">
	  <div class="block-container">
		  <div id="PostAreas">			  		  
			  ';
	if ($__vars['postAreas']) {
		$__finalCompiled .= '
				<table>
				  <thead>
					<tr> 
					  <td class=\'header forum_title_column\'>' . 'Forum' . '</td>
					  ';
		if ($__vars['hasCreatedAThread']) {
			$__finalCompiled .= '
						 <td class=\'header thread_counts_column\'> ' . 'Threads' . ' </td>
					  ';
		}
		$__finalCompiled .= '
					  <td class=\'header post_counts_column\'> ' . 'Posts' . ' </td>        
				  </thead>    
				  <tbody>
					';
		if ($__templater->isTraversable($__vars['postAreas'])) {
			foreach ($__vars['postAreas'] AS $__vars['key'] => $__vars['entry']) {
				$__finalCompiled .= '
					<tr>
						  <td class="forum_title_column"> <a href=\'' . $__templater->func('link', array('forums', array('node_id' => $__vars['postAreas'][$__vars['key']]['node_id'], ), ), true) . '\' rel="nofollow"> ' . $__templater->escape($__vars['entry']['title']) . ' </a> </td>
						  ';
				if ($__vars['hasCreatedAThread']) {
					$__finalCompiled .= '
							<td class="thread_counts_column"> 
								<a href=\'' . $__templater->func('link', array('search/threads-of-member', null, array('user_id' => $__vars['user']['user_id'], 'node' => $__vars['postAreas'][$__vars['key']]['node_id'], ), ), true) . '\'>';
					if ($__vars['entry']['thread_count'] > 0) {
						$__finalCompiled .= $__templater->escape($__vars['entry']['thread_count']);
					}
					$__finalCompiled .= '</a>								
							</td>
						  ';
				}
				$__finalCompiled .= '
						  <td class="post_counts_column"> 
							  <a href=\'' . $__templater->func('link', array('search/search', null, array('search_type' => 'post', 'c[users]' => $__vars['user']['username'], 'c[nodes][]' => $__vars['postAreas'][$__vars['key']]['node_id'], ), ), true) . '\'>' . $__templater->escape($__vars['entry']['post_count']) . '</a>						
						  </td>
					</tr> 
				  ';
			}
		}
		$__finalCompiled .= '
				  </tbody>
				</table>
			  ';
	} else {
		$__finalCompiled .= '
				<span> ' . 'This user hasn\'t posted anything yet.' . ' </span>
			  ';
	}
	$__finalCompiled .= '
	  </div>
  </div>
</div>';
	return $__finalCompiled;
}
);