<?php

namespace Snog\Forms\Widget;

use XF\Widget\AbstractWidget;

class Forms extends AbstractWidget
{
	public function render()
	{
		$options = $this->options;

		/** @var \Snog\Forms\Entity\Form[] $formValues */
		$formValues = $this->finder('Snog\Forms:Form')
			->with('Type')
			->where('active', 1)
			->order('Type.display', 'ASC')
			->order('display', 'ASC')
			->fetch();

		/** @var \Snog\Forms\XF\Entity\User $user */
		$user = \XF::visitor();
		$headerValues = [];

		// ONLY DISPLAY FORMS THE USER MEETS CRITERIA FOR
		foreach ($formValues as $key => $value)
		{
			if ($value->formlimit && $userSubmitCount = $user->getAdvancedFormsSubmitCount($value))
			{
				if ($value->formlimit >= $userSubmitCount)
				{
					unset($formValues[$key]);
				}
			}

			$isActive = $value->isFormDateActive($error);
			if (!$isActive)
			{
				unset($formValues[$key]);
				continue;
			}

			if ($value->Type && $value->Type->user_criteria)
			{
				$isMatched = $value->Type->checkUserCriteriaMatch($user);

				if ($value->Type->active && $value->Type->sidebar && $isMatched)
				{
					$isMatched = $value->checkUserCriteriaMatch($user, false, false);
					if (!$isMatched) unset($formValues[$key]);
				}
				else
				{
					unset($formValues[$key]);
				}
			}
			else
			{
				$isMatched = $value->checkUserCriteriaMatch($user, false, false);
				if (!$isMatched || !$value->active) unset($formValues[$key]);
			}
		}

		// EXTRACT FORM TYPES FOR DISPLAY
		foreach ($formValues as $formHeader)
		{
			if (isset($formHeader->Type->type) && $formHeader->Type->sidebar && !in_array($formHeader->Type->type, $headerValues))
			{
				$headerValues[] = $formHeader->Type->type;
			}
			if (empty($formHeader->Type))
			{
				$headerValues[] = null;
			}
		}

		$viewParams = [
			'forms' => $formValues,
			'headervalues' => $headerValues,
			'style' => $options['style']
		];

		return $this->renderer('snog_forms_widget', $viewParams);
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'style' => 'str',
		]);

		return true;
	}
}