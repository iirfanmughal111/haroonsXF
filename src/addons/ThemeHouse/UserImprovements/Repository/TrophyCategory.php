<?php

namespace ThemeHouse\UserImprovements\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class TrophyCategory extends Repository
{
    /**
     * @return Finder
     */
    public function findTrophyCategoriesForList()
    {
        return $this->finder('ThemeHouse\UserImprovements:TrophyCategory')->order('display_order');
    }
}
