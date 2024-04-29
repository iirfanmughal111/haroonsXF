<?php

namespace nick97\TraktMovies\XF\Pub\Controller;

class Account extends XFCP_Account
{
	public function actionPreferences()
	{
		$reply = parent::actionPreferences();

		if ($reply instanceof \XF\Mvc\Reply\View) {
			/** @var \nick97\TraktMovies\Data\Country $countryData */
			$countryData = $this->app()->data('nick97\TraktMovies:Country');
			$countryList = $countryData->getCountryOptions(true);

			$reply->setParam('traktMoviesWatchRegions', $countryList);
		}

		return $reply;
	}

	protected function preferencesSaveProcess(\XF\Entity\User $visitor)
	{
		$form = parent::preferencesSaveProcess($visitor);

		$allowedCountries = $this->app()->options()->traktthreads_watchProviderRegions;
		if (!in_array('', $allowedCountries)) {
			$input = $this->filter(['option' => ['nick97_movies_trakt_watch_region' => 'str']]);

			/** @var \XF\Entity\UserOption $userOptions */
			$userOptions = $visitor->getRelationOrDefault('Option');
			$form->setupEntityInput($userOptions, $input['option']);
		}

		return $form;
	}
}
