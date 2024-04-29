<?php

namespace Snog\Forms\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Import extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('snogFormsAdmin');
	}

	public function actionIndex()
	{
		if ($this->isPost())
		{
			$input = $this->filter([
				'mode' => 'str',
				'directory' => 'str',
				'merge' => 'uint'
			]);

			if (!$input['directory']) $input['directory'] = 'data/forms-export.xml';

			/** @var \Snog\Forms\Service\Import $importService */
			$importService = $this->service('Snog\Forms:Import');
			$importService->setDoMerge($input['merge']);

			if ($input['mode'] == 'upload')
			{
				$upload = $this->request->getFile('upload', false);
				if (!$upload) return $this->error(\XF::phrase('snog_forms_import_valid_file'));

				try
				{
					$xml = \XF\Util\Xml::openFile($upload->getTempFile());
				}
				catch (\Exception $e)
				{
					$xml = null;
				}

				if (!$xml || $xml->getName() !== 'Forms_export')
				{
					return $this->error(\XF::phrase('snog_forms_import_valid_file'));
				}

				$importService->importXml($xml);
			}
			else
			{
				try
				{
					$xml = \XF\Util\Xml::openFile($input['directory']);
				}
				catch (\Exception $e)
				{
					$xml = null;
				}

				if (!$xml || $xml->getName() !== 'Forms_export')
				{
					return $this->error(\XF::phrase('snog_forms_import_valid_file'));
				}

				$importService->importXml($xml);
			}

			return $this->redirect($this->buildLink('form-import', null, ['success' => true]));
		}

		$viewParams = [
			'success' => $this->filter('success', 'bool')
		];

		return $this->view('Snog\Forms:Import', 'snog_forms_import', $viewParams);
	}
}