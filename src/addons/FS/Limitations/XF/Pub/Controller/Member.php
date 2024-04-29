<?php

namespace FS\Limitations\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Member extends XFCP_Member
{
    public function actionAbout(ParameterBag $params)
    {
        $parent =  parent::actionAbout($params);

        /** @var \XF\Entity\User $user */
        $user = $this->assertRecordExists('XF:User', $params->user_id);

        /** @var \XFMG\ControllerPlugin\MediaList $mediaListPlugin */
        $mediaListPlugin = $this->plugin('XFMG:MediaList');

        $categoryParams = $mediaListPlugin->getCategoryListData();
        $viewableCategoryIds = $categoryParams['viewableCategories']->keys();

        $listParams = $mediaListPlugin->getMediaListDataFsLimitations($viewableCategoryIds, $params->page, $user);

        $this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['totalItems'], 'media/users', $user);
        $this->assertCanonicalUrl($this->buildLink('media/users', $user, ['page' => $listParams['page']]));

        $parent->setParams($categoryParams + $listParams);

        return $parent;
    }
}
