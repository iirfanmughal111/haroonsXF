<?php

namespace FS\ThreadThumbnail\XF\Entity;

class Forum extends XFCP_Forum
{

    /**
     * @return \XF\Repository\ThreadPrefix
     */
    public function getPrefixesGroups()
    {

        /** @var \XF\Repository\ThreadPrefix $prefixRepo */
        $prefixRepo = $this->repository('XF:ThreadPrefix');
        $prefixListData = $prefixRepo->getPrefixListData();

        return $prefixListData;
    }
}
