<?php

namespace devsell\BitcoinPayments;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	public function installStep1()
    {
        $this->db()->insert('xf_payment_provider', [
            'provider_id' => 'ncp_coinbase_commerce',
            'provider_class' => 'devsell\\BitcoinPayments:CoinbaseCommerce',
            'addon_id' => 'devsell/BitcoinPayments'
        ]);
    }
}