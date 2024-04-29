<?php

namespace FS\AutoForumManager\Pub\Controller;

use XF\Mvc\Entity\Structure;
use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum
{
    public function actionIndex(ParameterBag $params)
    {
        if ($params->node_id) {
            $finder = \XF::finder('XF:Node')->where('node_id', $params->node_id)->where('display_in_list', 0)->fetchOne();

            if ($finder) {
                throw $this->exception(
                    $this->notFound(\XF::phrase("do_not_have_permission"))
                );
            }
        }
        return parent::actionIndex($params);
    }
}
