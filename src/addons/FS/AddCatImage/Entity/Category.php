<?php

namespace FS\AddCatImage\Entity;

use XF\Mvc\Entity\Structure;

class Category extends XFCP_Category
{
    public function getImgUrl($canonical = true)
    {
        $category_id = $this->resource_category_id;
        $path = sprintf('CategoryImage/' . '/%d/%d.jpg', floor($category_id / 1000), $category_id);
        return \XF::app()->applyExternalDataUrl($path, $canonical);
    }

    public function getAbstractedCustomImgPath()
    {
        $category_id = $this->resource_category_id;

        return sprintf('data://CategoryImage/' . '/%d/%d.jpg', floor($category_id / 1000), $category_id);
    }

    public function isImage()
    {
        $fs = $this->app()->fs();

        $ImgPath = $this->getAbstractedCustomImgPath();

        if ($fs->has($ImgPath)) {
            return 'true';
        }
    }
}
