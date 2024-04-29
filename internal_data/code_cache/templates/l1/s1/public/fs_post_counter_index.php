<?php
// FROM HASH: f020c5877131f16f699cd9e095ab383e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('' . $__templater->escape($__vars['user']['username']) . '\'s post counts');
	$__finalCompiled .= '

';
	$__templater->includeCss('fs_post_counter_tab_content.css');
	$__finalCompiled .= '

<div class="block">
	  <div class="block-container">
		  <div id="PostAreas">			  		  
			  ';
	if ($__vars['postCounters'] AND $__templater->func('count', array($__vars['postCounters'], ), false)) {
		$__finalCompiled .= '
				<table>
				  <thead>
					<tr> 
					  <td class=\'header forum_title_column\'>' . 'Forum' . '</td>
					
						 <td class=\'header thread_counts_column\'> ' . 'Threads' . ' </td>
				
					  <td class=\'header post_counts_column\'> ' . 'Posts' . ' </td>        
				  </thead>    
				  <tbody>
					';
		if ($__templater->isTraversable($__vars['postCounters'])) {
			foreach ($__vars['postCounters'] AS $__vars['key'] => $__vars['entry']) {
				$__finalCompiled .= '
					<tr>
						  <td class="forum_title_column">  <a href="' . $__templater->func('link', array('forums', array('node_id' => $__vars['entry']['node_id'], ), ), true) . '" rel="nofollow"> ' . $__templater->escape($__vars['entry']['Forum']['title']) . ' </a> </td>
						 
							<td class="thread_counts_column"> 
							';
				if ($__vars['entry']['thread_count'] > 0) {
					$__finalCompiled .= '
								<a href="' . $__templater->func('link', array('search/threads-of-member', null, array('user_id' => $__vars['user']['user_id'], 'node' => $__vars['entry']['node_id'], ), ), true) . '">
										' . $__templater->escape($__vars['entry']['thread_count']) . '
								</a>	
								';
				} else {
					$__finalCompiled .= '
									<span style="color: #2577b1;">0</span>
							';
				}
				$__finalCompiled .= '							
							</td>
					
						  <td class="post_counts_column"> 
							  <a href="' . $__templater->func('link', array('search/search', null, array('search_type' => 'post', 'c[users]' => $__vars['user']['username'], 'c[nodes][]' => $__vars['entry']['node_id'], ), ), true) . '">' . $__templater->escape($__vars['entry']['post_count']) . '</a>						
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
				  <div class="blockMessage"> ' . 'This user hasn\'t posted anything yet.' . ' </div>
			  ';
	}
	$__finalCompiled .= '
	  </div>
  </div>
</div>';
	return $__finalCompiled;
}
);