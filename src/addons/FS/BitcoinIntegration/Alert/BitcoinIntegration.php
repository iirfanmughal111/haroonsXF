<?php

namespace FS\BitcoinIntegration\Alert;

use XF\Mvc\Entity\Entity;
use XF\Alert\AbstractHandler;

class BitcoinIntegration extends AbstractHandler
{
    public function getOptOutActions()
    {
        return [
            'send_alert',
        ];
    }

    public function getOptOutDisplayOrder()
    {
        return 200;
    }

    public function canViewContent(Entity $entity, &$error = null)
    {
        return true;
    }
}
