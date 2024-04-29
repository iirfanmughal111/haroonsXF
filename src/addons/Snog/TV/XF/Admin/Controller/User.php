<?php

namespace Snog\TV\XF\Admin\Controller;

use XF\Mvc\ParameterBag;

class User extends XFCP_User
{
	public function actionEdit(ParameterBag $params)
	{
		$reply = parent::actionEdit($params);

		if ($reply instanceof \XF\Mvc\Reply\View)
		{
			/** @var \Snog\TV\Data\Country $countryData */
			$countryData = $this->app()->data('Snog\TV:Country');
			$countryList = $countryData->getCountryOptions();

			$allowedCountries = $this->app()->options()->TvThreads_watchProviderRegions;

			$countryList = array_filter($countryList, function ($country) use ($allowedCountries) {
				return in_array($country, $allowedCountries);
			}, ARRAY_FILTER_USE_KEY);

			$reply->setParam('snogTvWatchRegions', $countryList);
		}

		return $reply;
	}

	protected function userSaveProcess(\XF\Entity\User $user)
	{
		$formAction = parent::userSaveProcess($user);

		$allowedCountries = $this->app()->options()->TvThreads_watchProviderRegions;
		if (!in_array('', $allowedCountries))
		{
			$input = $this->filter(['option' => ['snog_tv_tmdb_watch_region' => 'str']]);

			/** @var \XF\Entity\UserOption $userOptions */
			$userOptions = $user->getRelationOrDefault('Option');
			$formAction->setupEntityInput($userOptions, $input['option']);
		}

		return $formAction;
	}
}