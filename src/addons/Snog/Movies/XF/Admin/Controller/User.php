<?php

namespace Snog\Movies\XF\Admin\Controller;

use XF\Mvc\ParameterBag;

class User extends XFCP_User
{
	public function actionEdit(ParameterBag $params)
	{
		$reply = parent::actionEdit($params);

		if ($reply instanceof \XF\Mvc\Reply\View)
		{
			/** @var \Snog\Movies\Data\Country $countryData */
			$countryData = $this->app()->data('Snog\Movies:Country');
			$countryList = $countryData->getCountryOptions();

			$allowedCountries = $this->app()->options()->tmdbthreads_watchProviderRegions;

			$countryList = array_filter($countryList, function ($country) use ($allowedCountries) {
				return in_array($country, $allowedCountries);
			}, ARRAY_FILTER_USE_KEY);

			$reply->setParam('snogMoviesWatchRegions', $countryList);
		}

		return $reply;
	}

	protected function userSaveProcess(\XF\Entity\User $user)
	{
		$formAction = parent::userSaveProcess($user);
		$allowedCountries = $this->app()->options()->tmdbthreads_watchProviderRegions;
		if (!in_array('', $allowedCountries))
		{
			$input = $this->filter(['option' => ['snog_movies_tmdb_watch_region' => 'str']]);

			/** @var \XF\Entity\UserOption $userOptions */
			$userOptions = $user->getRelationOrDefault('Option');
			$formAction->setupEntityInput($userOptions, $input['option']);
		}
		return $formAction;
	}
}