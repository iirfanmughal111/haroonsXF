<?php

namespace XenBulletins\BrandHub;

use XF\Util\Arr;

class Listener {


    public static function appSetup(\XF\App $app) {
        $container = $app->container();

        $container['customFields.bhItemfield'] = $app->fromRegistry('bhItemfield', function(\XF\Container $c) {
            return $c['em']->getRepository('XenBulletins\BrandHub:ItemField')->rebuildFieldCache();
        }, function(array $mediaFieldsInfo) {
            $definitionSet = new \XF\CustomField\DefinitionSet($mediaFieldsInfo);

            return $definitionSet;
        }
        );
    }

}
