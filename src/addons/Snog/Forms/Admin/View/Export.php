<?php

namespace Snog\Forms\Admin\View;

use XF\Mvc\View;

class Export extends View
{
	public function renderXml()
	{
		/** @var \DOMDocument $document */
		$document = $this->params['xml'];
		$this->response->setDownloadFileName('forms-export.xml');
		return $document->saveXML();
	}

	public function renderRaw()
	{
		$document = $this->params['csv'];

		$this->response
			->setDownloadFileName('forms-exp1ort.csv')
			->header('Content-Encoding', 'none')
			->header('Content-Type', 'text/csv; charset=utf-8');

		return $document;
	}
}