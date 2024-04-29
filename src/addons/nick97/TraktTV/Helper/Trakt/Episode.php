<?php

namespace nick97\TraktTV\Helper\Trakt;

class Episode
{
	public function parseCast(array $apiResponse, $withGuestStars = true): array
	{
		$casts = [];
		foreach ($apiResponse['credits']['cast'] as $cast) {
			$casts[] = $cast['name'];
		}

		if ($withGuestStars) {
			foreach ($apiResponse['credits']['guest_stars'] as $guestStar) {
				if (!in_array($guestStar['name'], $casts)) {
					$casts[] = $guestStar['name'];
				}
			}
		}

		return $casts;
	}

	public function getCastList(array $apiResponse, $withGuestStars = true)
	{
		return implode(',', $this->parseCast($apiResponse, $withGuestStars));
	}

	public function parseGuestStars(array $apiResponse): array
	{
		$permStars = $this->parseCast($apiResponse, false);
		$guestStars = [];

		if (isset($apiResponse['credits']['guest_stars'])) {
			foreach ($apiResponse['credits']['guest_stars'] as $guestStar) {
				if (!in_array($guestStar['name'], $permStars)) {
					$guestStars[] = $guestStar['name'];
				}
			}
		}

		return $guestStars;
	}

	public function getGuestStarsList(array $apiResponse, $withGuestStars = true)
	{
		return implode(',', $this->parseGuestStars($apiResponse));
	}
}
