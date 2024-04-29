<?php

namespace FS\BitcoinIntegration\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;
use XF\Mvc\RouteMatch;

class BitcoinIntegration extends AbstractController
{
	public function actionIndex(ParameterBag $params)
	{
		$visitor = \XF::visitor();

		$viewParams = [];

		// if ($visitor->account_type == 1) {
		// 	$groupsParams = $this->menUpgradeCards();
		// } else if ($visitor->account_type == 2) {
		// 	$groupsParams = $this->womenUpgradeCards();
		// } else {
		// 	return $this->noPermission();
		// }

		if ($visitor->user_id == 2) {
			$viewParams = $this->menUpgradeCards();
		} else if ($visitor->user_id == 1) {
			$viewParams = $this->womenUpgradeCards();
		} else {
			return $this->noPermission();
		}

		return $this->view('fS\BitcoinIntegration:index', 'fs_bitcoin_upgrade_cards_index', $viewParams);
	}

	public function actionCompanion()
	{
		$visitor = \XF::visitor();

		if (!$visitor->user_id) {
			return $this->noPermission();
		}

		$viewParams = $this->womenUpgradeCards();

		$viewParams += ['women' => true];

		return $this->view('fS\BitcoinIntegration:companion', 'fs_bitcoin_upgrade_cards_nav', $viewParams);
	}

	public function actionAdmirer()
	{
		$visitor = \XF::visitor();

		if (!$visitor->user_id) {
			return $this->noPermission();
		}

		$viewParams = $this->menUpgradeCards();

		$viewParams += ['men' => true];

		return $this->view('fS\BitcoinIntegration:admirer', 'fs_bitcoin_upgrade_cards_nav', $viewParams);
	}

	protected function menUpgradeCards()
	{
		$sixMonthUpgradeId = \xf::options()->fs_bitcoin_six_month;
		$oneYearUpgradeId = \xf::options()->fs_bitcoin_one_year;

		$app = \xf::app();

		if (!$oneYearUpgradeId  || !$sixMonthUpgradeId) {
			($this->notFound(\XF::phrase("Admin options setting reuired...!"))
			);
		}

		$sixMonthUpgrade = $app->em()->find('XF:UserUpgrade', $sixMonthUpgradeId);
		$oneYearUpgrade = $app->em()->find('XF:UserUpgrade', $oneYearUpgradeId);


		$groupParams = [
			'sixMonthUpgrade' => $sixMonthUpgrade,
			'oneYearUpgrade' => $oneYearUpgrade
		];

		return $groupParams;
	}

	protected function womenUpgradeCards()
	{
		$premiumUpgradeId = \xf::options()->fs_bitcoin_premium_companion;
		$providerCityUpgradeId = \xf::options()->fs_bitcoin_provider_city;
		$vipUpgradeId = \xf::options()->fs_bitcoin_vip_companion;
		$providerVipUpgradeId = \xf::options()->fs_bitcoin_provider_vip;

		$app = \xf::app();

		if (!$premiumUpgradeId || !$providerCityUpgradeId || !$vipUpgradeId  || !$providerVipUpgradeId) {
			($this->notFound(\XF::phrase("Admin options setting reuired...!"))
			);
		}

		$premiumUpgrade = $app->em()->find('XF:UserUpgrade', $premiumUpgradeId);
		$providerCityUpgrade = $app->em()->find('XF:UserUpgrade', $providerCityUpgradeId);
		$vipUpgrade = $app->em()->find('XF:UserUpgrade', $vipUpgradeId);
		$providerVipUpgrade = $app->em()->find('XF:UserUpgrade', $providerVipUpgradeId);

		$groupParams = [
			'premiumUpgrade' => $premiumUpgrade,
			'providerCityUpgrade' => $providerCityUpgrade,
			'vipUpgrade' => $vipUpgrade,
			'providerVipUpgrade' => $providerVipUpgrade,
		];

		return $groupParams;
	}

	protected function checkExisting($userId, $upgradeGroupId)
	{
		$groupExist = $this->finder('FS\BitcoinIntegration:PurchaseRec')
			->where('user_id', $userId)->where('user_upgrade_id', $upgradeGroupId)->where('status', 2)->where('end_at', '>', \XF::$time)->fetchOne();

		return $groupExist ? true : false;
	}

	public function actionPurchase()
	{
		$optionValue = $this->filter('optionValue', 'str');

		if (!$optionValue) {
			throw $this->exception(
				$this->notFound(\XF::phrase("complete Admin option Setting....!"))
			);
		}

		$widgetId = $optionValue . '_widget_uid';
		$userUpgradeId = \xf::options()->$optionValue;

		$widgetuuId = \xf::options()->$widgetId;

		$app = \xf::app();

		if (!$userUpgradeId || !$widgetuuId) {

			throw $this->exception(
				$this->notFound(\XF::phrase("complete Admin option Setting.....!"))
			);
		}

		$userUpgrade = $app->em()->find('XF:UserUpgrade', $userUpgradeId);

		if (!$userUpgrade) {
			throw $this->exception(
				$this->notFound(\XF::phrase("User upgrade not found.....!"))
			);
		}

		$visitor = \xf::visitor();

		// $this->isAllowed($userUpgradeId);

		$premiumExist = $this->checkExisting($visitor->user_id, $userUpgradeId);

		if ($premiumExist && $userUpgrade->getUserUpgradeExit()) {
			throw $this->exception(
				$this->noPermission()
			);
		}

		$data = $this->insertPrucase($visitor->user_id, $userUpgradeId);

		$encryptArray = $this->encrypt(array($data->id, $visitor->user_id, $userUpgradeId, \xf::$time));

		$viewParams = [
			'widgetId' => $widgetuuId,
			'userUpgrade' => $userUpgrade,
			'encrypt' => $encryptArray,
		];
		return $this->view('FS\BitcoinIntegration:Bitcoin\Purchase', '', $viewParams);
	}

	protected function isAllowed($id)
	{
		$visitor = \xf::visitor();

		if (!$visitor->user_id) {
			throw $this->exception(
				$this->noPermission()
			);
		}

		// if ($visitor->account_type == 1) {

		// 	$sixMonthUpgradeId = \xf::options()->fs_bitcoin_six_month;
		// 	$oneYearUpgradeId = \xf::options()->fs_bitcoin_one_year;

		// 	if (!($sixMonthUpgradeId == $id || $oneYearUpgradeId == $id)) {
		// 		throw $this->exception(
		// 			$this->noPermission()
		// 		);
		// 	}
		// } elseif ($visitor->account_type == 2) {
		// 	$premiumUpgradeId = \xf::options()->fs_bitcoin_premium_companion;
		// 	$providerCityUpgradeId = \xf::options()->fs_bitcoin_provider_city;
		// 	$vipUpgradeId = \xf::options()->fs_bitcoin_vip_companion;
		// 	$providerVipUpgradeId = \xf::options()->fs_bitcoin_provider_vip;

		// 	if (!($premiumUpgradeId == $id || $providerCityUpgradeId == $id || $vipUpgradeId == $id || $providerVipUpgradeId == $id)) {
		// 		throw $this->exception(
		// 			$this->noPermission()
		// 		);
		// 	}
		// }
	}

	public function insertPrucase($userId, $upgradeId)
	{
		$insertData = $this->em()->create('FS\BitcoinIntegration:PurchaseRec');

		$insertData->user_id = $userId;
		$insertData->user_upgrade_id = $upgradeId;
		$insertData->save();

		return $insertData;
	}

	public function encrypt(array $data)
	{
		$data = json_encode($data);

		$packed = unpack('H*', $data);

		return $packed[1];
	}
}
