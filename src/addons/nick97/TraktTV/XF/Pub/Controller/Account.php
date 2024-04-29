<?php

namespace nick97\TraktTV\XF\Pub\Controller;

class Account extends XFCP_Account
{
	public function actionPreferences()
	{
		$reply = parent::actionPreferences();

		if ($reply instanceof \XF\Mvc\Reply\View) {
			/** @var \nick97\TraktTV\Data\Country $countryData */
			$countryData = $this->app()->data('nick97\TraktTV:Country');
			$countryList = $countryData->getCountryOptions(true);

			$reply->setParam('traktTvWatchRegions', $countryList);
		}

		return $reply;
	}

	protected function preferencesSaveProcess(\XF\Entity\User $visitor)
	{
		$form = parent::preferencesSaveProcess($visitor);

		$allowedCountries = $this->app()->options()->traktTvThreads_watchProviderRegions;
		if (!in_array('', $allowedCountries)) {
			$input = $this->filter(['option' => ['nick97_tv_trakt_watch_region' => 'str']]);

			/** @var \XF\Entity\UserOption $userOptions */
			$userOptions = $visitor->getRelationOrDefault('Option');
			$form->setupEntityInput($userOptions, $input['option']);
		}

		return $form;
	}
}
