<?php

namespace Snog\Movies\XF\Pub\Controller;

class Account extends XFCP_Account
{
	public function actionPreferences()
	{
		$reply = parent::actionPreferences();

		if ($reply instanceof \XF\Mvc\Reply\View)
		{
			/** @var \Snog\Movies\Data\Country $countryData */
			$countryData = $this->app()->data('Snog\Movies:Country');
			$countryList = $countryData->getCountryOptions(true);

			$reply->setParam('snogMoviesWatchRegions', $countryList);
		}

		return $reply;
	}

	protected function preferencesSaveProcess(\XF\Entity\User $visitor)
	{
		$form = parent::preferencesSaveProcess($visitor);

		$allowedCountries = $this->app()->options()->tmdbthreads_watchProviderRegions;
		if (!in_array('', $allowedCountries))
		{
			$input = $this->filter(['option' => ['snog_movies_tmdb_watch_region' => 'str']]);

			/** @var \XF\Entity\UserOption $userOptions */
			$userOptions = $visitor->getRelationOrDefault('Option');
			$form->setupEntityInput($userOptions, $input['option']);
		}

		return $form;
	}
}