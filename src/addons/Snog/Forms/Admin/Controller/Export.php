<?php

namespace Snog\Forms\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;


class Export extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('snogFormsAdmin');
	}

	public function actionIndex()
	{
		if ($this->isPost())
		{
			$mode = $this->filter('mode', 'str');
			$data = $this->filter('data', 'array-str');

			if (!$data)
			{
				throw $this->exception($this->error(\XF::phrase('please_enter_value_for_all_required_fields')));
			}

			/** @var \Snog\Forms\Service\Export $exportService */
			$exportService = $this->service('Snog\Forms:Export');
			$exportService->setExportData($data);

			if ($mode == 'csv')
			{
				$phpVersion = phpversion();
				if (version_compare($phpVersion, '7.4.0', '<'))
				{
					throw $this->errorException(\XF::phrase('snog_forms_csv_export_php_min_x_version', ['version' => '7.4.0']));
				}

				$csv = $exportService->exportToCsv();
				$viewParams = ['csv' => $csv->getContent()];
				$this->setResponseType('raw');
			}
			else
			{
				$xml = $exportService->exportToXml();
				$viewParams = ['xml' => $xml];
				$this->setResponseType('xml');
			}

			return $this->view('Snog\Forms:Export', '', $viewParams);
		}

		return $this->view('Snog\Forms:Export', 'snog_forms_export', []);
	}
}