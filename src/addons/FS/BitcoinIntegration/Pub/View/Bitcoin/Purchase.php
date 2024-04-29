<?php

namespace FS\BitcoinIntegration\Pub\View\Bitcoin;

use XF\Mvc\View;

class Purchase extends View
{
	public function renderJson()
	{
		$encrypt = $this->params['encrypt'];
		$userUpgrade = $this->params['userUpgrade'];
		$widgetId = $this->params['widgetId'];

		$PurchaeEncryptTemplate = $this->renderTemplate("public:fs_encrpt_bitcoin_purchase", ['encrypt' => $encrypt, 'userUpgrade' => $userUpgrade, 'widgetId' => $widgetId]);

		return [
			'html' => $this->renderer->getHtmlOutputStructure($PurchaeEncryptTemplate),
		];
	}
}
