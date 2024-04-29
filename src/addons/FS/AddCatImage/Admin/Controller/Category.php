<?php

namespace FS\AddCatImage\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

class Category extends XFCP_Category
{

	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();

		if ($params->resource_category_id) {
			$category = $this->assertCategoryExists($params->resource_category_id);
		} else {
			$category = $this->em()->create('XFRM:Category');
		}

		$this->categorySaveProcess($category)->run();

		$this->saveImage($category);

		return $this->redirect(
			$this->buildLink('resource-manager/categories') . $this->buildLinkHash($category->getEntityId())
		);
	}

	protected function saveImage($category)
	{
		$uploads['image'] = $this->request->getFile('image', false, false);

		if ($uploads['image']) {
			$uploadService = $this->service('FS\AddCatImage:Upload', $category);

			if (!$uploadService->setImageFromUpload($uploads['image'])) {
				return $this->error($uploadService->getError());
			}

			if (!$uploadService->uploadCategoryImage()) {
				return $this->error(\XF::phrase('new_image_could_not_be_processed'));
			}
		}
	}

	public function actionDelete(ParameterBag $params)
	{
		$categoryImage = $this->assertCategoryExists($params->resource_category_id);

		if ($this->isPost()) {
			$fs = $this->app()->fs();

			$ImgPath = $categoryImage->getAbstractedCustomImgPath();

			if ($fs->has($ImgPath)) {
				$fs->delete($ImgPath);
			}
		}
		return $this->getCategoryTreePlugin()->actionDelete($params);
	}
}
