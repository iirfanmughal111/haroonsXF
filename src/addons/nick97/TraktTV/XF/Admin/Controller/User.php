<?php

namespace nick97\TraktTV\XF\Admin\Controller;

use XF\Mvc\ParameterBag;

class User extends XFCP_User
{
	public function actionEdit(ParameterBag $params)
	{
		$reply = parent::actionEdit($params);

		if ($reply instanceof \XF\Mvc\Reply\View) {
			/** @var \nick97\TraktTV\Data\Country $countryData */
			$countryData = $this->app()->data('nick97\TraktTV:Country');
			$countryList = $countryData->getCountryOptions();

			$allowedCountries = $this->app()->options()->traktTvThreads_watchProviderRegions;

			$countryList = array_filter($countryList, function ($country) use ($allowedCountries) {
				return in_array($country, $allowedCountries);
			}, ARRAY_FILTER_USE_KEY);

			$reply->setParam('traktTvWatchRegions', $countryList);
		}

		return $reply;
	}

	protected function userSaveProcess(\XF\Entity\User $user)
	{
		$formAction = parent::userSaveProcess($user);

		$allowedCountries = $this->app()->options()->traktTvThreads_watchProviderRegions;
		if (!in_array('', $allowedCountries)) {
			$input = $this->filter(['option' => ['nick97_tv_trakt_watch_region' => 'str']]);

			/** @var \XF\Entity\UserOption $userOptions */
			$userOptions = $user->getRelationOrDefault('Option');
			$formAction->setupEntityInput($userOptions, $input['option']);
		}

		return $formAction;
	}
}
