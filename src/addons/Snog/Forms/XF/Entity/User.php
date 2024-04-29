<?php

namespace Snog\Forms\XF\Entity;


/**
 * Class User
 * @package Snog\Forms\XF\Entity
 *
 * @property array $snog_forms
 */
class User extends XFCP_User
{
	public function canViewAdvancedForms(&$error = null)
	{
		return $this->hasPermission('snogForms', 'canViewForms');
	}

	public function canApproveAdvancedForms(&$error = null)
	{
		return $this->hasPermission('snogForms', 'canApproveForms');
	}

	public function canExtendAdvancedFormsPolls(&$error = null)
	{
		return $this->hasPermission('snogForms', 'canExtendPolls');
	}

	public function getAdvancedFormsSubmitCount(\Snog\Forms\Entity\Form $form)
	{
		if ($form->formlimit && !is_null($this->snog_forms))
		{
			foreach ($this->snog_forms as $check)
			{
				if ($form->posid == $check['posid'])
				{
					return $check['count'];
				}
			}
		}

		return 0;
	}

	public function adjustAdvancedFormsSubmitCount(\Snog\Forms\Entity\Form $form, $direction)
	{
		$changeForms = [];

		if (!is_null($this->snog_forms))
		{
			$formFound = false;
			$checkForms = $this->snog_forms;

			if ($checkForms)
			{
				foreach ($checkForms as $check)
				{
					if ($check['posid'] == $form->posid)
					{
						$check['count'] = $check['count'] + $direction;
						$formFound = true;
					}

					$changeForms[] = $check;
				}
			}

			if (!$formFound)
			{
				$changeForms[] = ['posid' => $form->posid, 'count' => $direction];
			}
		}
		else
		{
			$changeForms[] = ['posid' => $form->posid, 'count' => $direction];
		}

		$this->snog_forms = $changeForms;
		return $changeForms;
	}

	public function updateAdvancedFormsSerials($posId)
	{
		$oldSerials = $this->snog_forms;
		$newSerial = [];

		if ($oldSerials)
		{
			foreach ($oldSerials as $oldSerial)
			{
				if ($oldSerial['posid'] <> $posId)
				{
					$newSerial[] = $oldSerial;
				}
			}
		}

		$this->snog_forms = $newSerial;
	}
}
