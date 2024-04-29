<?php
// FROM HASH: 8f49bedb41b256f119df46403e878e31
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeJs(array(
		'src' => 'xf/login_signup.js',
		'min' => '1',
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Moderation Guidelines');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['xf']['options']['registrationTimer']) {
		$__compilerTemp1 .= '
					<span id="js-regTimer" data-timer-complete="' . $__templater->filter('Accept', array(array('for_attr', array()),), true) . '">
						' . $__vars['xf']['language']['parenthesis_open'] . 'Please wait ' . ('<span>' . $__templater->escape($__vars['xf']['options']['registrationTimer']) . '</span>') . ' second(s).' . $__vars['xf']['language']['parenthesis_close'] . '
					</span>
					';
	} else {
		$__compilerTemp1 .= '
					' . 'Accept' . '
				';
	}
	$__finalCompiled .= $__templater->form('

	<div class="block-container">
		<div class="block-body" style="overflow: auto; height: 500px;">
			' . $__templater->formInfoRow('
			' . '<p style="text-align: justify;">I own this board and am the Administrator/Founder. I will be guided by this
Charter of Moderation when and if Admin action is needed. I Admin with the
handle Jovial Monk and post as HBS Guy--call me Monk in either case. I
lead an Admin/Mod team of such Admin/Mods as I see fit from time to time.</p>

<p style="text-align: justify;">A Forum Moderator may be appointed or elected for a part or parts of this
board.</p>

<p style="text-align: justify;">This board is for the discussion of politics and current affairs from an Australian perspective. It is open to those from the right as well as left, to those
from Australia and those from overseas. Off Topic is for discussing more personal stuff and board Admin. The Sand Pit is for more casual chat and some
of the rules are relaxed there. It is open to guest posting that will be moderated if abused.</p>

<p style="text-align: justify;">Members, visible only to Members, is for posting more personal stuff</p>

<p style="text-align: justify;"><strong style="font-size: large;">Validation of membership</strong><br/>New members may very occasionally be asked to provide a real email address and proxy addresses are frowned upon--you may need to justify use of
proxy addresses when asked by Admin or Moderator.</p>

<p style="text-align: justify;"><strong style="font-size: large;">Abuse and disputes</strong><br/>Please remember at all times: the internet, email is not the same as face to
face, it carries hardly any emotional content, no body language information,
no tone of voice etc etc, patience and goodwill are needed to get over the
odd bump. An insult followed by a smily can still be conceived of as an insult
rather than a joke. Please exercise restraint even when you believe insult was
intended.</p>

<p style="text-align: justify;">Admin & mods will not take sides in any disputes!</p>

<p style="text-align: justify;">Posts that are pure invective will be deleted except if they are really funny we
may leave it in place. Posts that publish personal details will be deleted and
the member banned. So keep the posts on topic and you can go hammer
and tongs. Swearing is allowed—I\'m not your mother—as long as a post is
not just pure invective. When discussing Political events/situations etc use of
strong language probably indicates your argument is not very well based so
swearing should be minimal there. Think of others who may well be offended.</p>

<p style="text-align: justify;">If attacking someone then attack them but not their family. Posts that breach
copyright will be deleted and the poster warned or suspended.</p>

<p style="text-align: justify;"><strong style="font-size: large;">Copyright of posts</strong><br/>When you post you request and allow us to publish your posts and, unless
you indicate otherwise copyright in that post resides with me. That permission to publish your posts is permanent.</p>

<p style="text-align: justify;">As a member here you can edit, delete those posts etc. If you manage to be
unpleasant enough to get banned your posts will remain here as a part of
this board.. Unless you specifically claim copyright over a post the copyright
in such a post belongs to me.</p>' . '
			', array(
	)) . '
			' . $__templater->formCheckBoxRow(array(
		'standalone' => 'true',
	), array(array(
		'name' => 'accept',
		'required' => 'required',
		'label' => 'I agree to the terms and conditions.',
		'_type' => 'option',
	)), array(
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
	), array(
		'html' => '
			' . $__templater->button('
				' . $__compilerTemp1 . '
			', array(
		'type' => 'submit',
		'class' => 'button--primary',
		'id' => 'js-signUpButton',
	), '', array(
	)) . '
			',
	)) . '
	</div>

', array(
		'action' => $__templater->func('link', array('register/', ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-xf-init' => 'reg-form',
	));
	return $__finalCompiled;
}
);