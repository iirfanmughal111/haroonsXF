<?php


namespace Snog\Forms\ControllerPlugin;


use Snog\Forms\Entity\Form as FormEntity;
use XF;
use XF\ControllerPlugin\AbstractPlugin;

class Form extends AbstractPlugin
{
	/**
	 * @param FormEntity $form
	 * @param \XF\Mvc\Entity\Entity|null $context
	 * @param bool $throw
	 * @param null $error
	 * @return bool
	 * @throws XF\Mvc\Reply\Exception
	 */
	public function assertUsableFormFromContext(FormEntity $form, \XF\Mvc\Entity\Entity $context = null, $throw = true, &$error = null)
	{
		// CHECK IF REPLY OPTION BUTTON PASSED THREAD ID
		if ($form->qroption)
		{
			// PREVENT INJECTION OF DIFFERENT FORUM/THREAD
			if ($context instanceof \XF\Entity\Thread)
			{
				if (!in_array($context->node_id, $form->qrforums))
				{
					$error = XF::phrase('do_not_have_permission');
					if ($throw)
					{
						throw $this->exception($this->noPermission($error));
					}
					else
					{
						return false;
					}
				}

				if ($form->qrstarter && $context->user_id !== \XF::visitor()->user_id)
				{
					$error = XF::phrase('do_not_have_permission');
					if ($throw)
					{
						throw $this->exception($this->noPermission($error));
					}
					else
					{
						return false;
					}
				}
			}
		}

		return true;
	}

	public function assertFormCooldown(FormEntity $form, \XF\Entity\User $user = null, $ipAddress = null, $throw = true)
	{
		if ($form->cooldown === 0)
		{
			return;
		}

		if (!$user)
		{
			$user = XF::visitor();
		}

		if (!$ipAddress)
		{
			$ipAddress = $this->request->getIp();
		}

		/** @var \Snog\Forms\Repository\Log $logRepo */
		$logRepo = $this->repository('Snog\Forms:Log');
		$logDate = $logRepo->getCooldownStartDate($form, $user, $ipAddress);

		if ($logDate > 0)
		{
			if ($form->cooldown === -1)
			{
				$error = XF::phrase('snog_forms_you_cant_submit_this_form_again');
			}
			else
			{
				$error = XF::phrase('snog_forms_you_cant_submit_this_form_after_x', [
					'time' => XF::language()->dateTime($logDate + $form->cooldown)
				]);
			}

			if ($throw)
			{
				throw $this->exception($this->error($error));
			}
		}

		return null;
	}
}