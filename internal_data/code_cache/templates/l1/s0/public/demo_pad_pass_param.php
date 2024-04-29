<?php
// FROM HASH: 3e3db8a19c722899a603fd6fb42e00dd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Get parameters and use below');
	$__finalCompiled .= '

<div class="block">
    <div class="block-container">
        <div class="block-body">
                <div class="block-row">
                    <p>' . $__templater->escape($__vars['string']) . ' Here is some content</p>
                    <p>The number is ' . $__templater->filter($__vars['number'], array(array('number', array()),), true) . '</p>
                    <p>I have ' . $__templater->filter($__vars['money'], array(array('currency', array('usd', )),), true) . '</p>
                    <p>Here is some more content</p>
                    <p>
                        <a href="' . $__templater->func('link', array('notes', ), true) . '">Back to the index</a>
                    </p>
                    <p>I have an array and the third element is ' . $__templater->escape($__vars['array']['2']) . '</p>
                    <ol>
                        ';
	if ($__templater->isTraversable($__vars['array'])) {
		foreach ($__vars['array'] AS $__vars['key'] => $__vars['value']) {
			$__finalCompiled .= '
                            <li>' . $__templater->escape($__vars['value']) . '</li>
                        ';
		}
	}
	$__finalCompiled .= '
                    </ol>
                    <hr />
                    <ol>
                        ';
	if ($__templater->isTraversable($__vars['array1'])) {
		foreach ($__vars['array1'] AS $__vars['key'] => $__vars['value']) {
			$__finalCompiled .= '
                            <li>
                                The <i>' . $__templater->escape($__vars['key']) . '</i> is <b>' . $__templater->escape($__vars['value']) . '</b>
                            </li>
                        ';
		}
	}
	$__finalCompiled .= '
                    </ol>
                    <p>The current date and time in unix is : ' . $__templater->escape($__vars['xf']['time']) . '</p>
                    <p>The current date and time is : ' . $__templater->func('date_time', array($__vars['xf']['time'], ), true) . '</p>
                    ';
	if ($__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
                        You are logged in, with user name <b>' . $__templater->escape($__vars['xf']['visitor']['username']) . '</b>.
                    ';
	} else {
		$__finalCompiled .= '
                        You are our guest.
                    ';
	}
	$__finalCompiled .= '

                    ' . $__templater->func('dump', array($__vars['xf'], ), true) . '
                </div>
        </div>
    </div>
</div>';
	return $__finalCompiled;
}
);