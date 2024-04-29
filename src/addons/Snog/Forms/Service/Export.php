<?php


namespace Snog\Forms\Service;


use XF\Service\AbstractService;

class Export extends AbstractService
{
	protected $exportData = [];

	public function setExportData(array $exportData)
	{
		$this->exportData = $exportData;
	}

	public function exportToXml()
	{
		$xml = $this->createXml();

		$insertData = function (array $data, string $typeName) use ($xml) {
			foreach ($data as $typeData)
			{
				$type = $xml->addChild($typeName);
				foreach ($typeData as $key => $value)
				{
					$type->addChild($key, $value);
				}
			}

			return $xml;
		};

		if (in_array('types', $this->exportData))
		{
			$typeExportData = $this->getTypeExportData();
			$xml = $insertData($typeExportData, 'Type');
		}

		if (in_array('forms', $this->exportData))
		{
			$formExportData = $this->getFormExportData();
			$xml = $insertData($formExportData, 'Form');
		}

		if (in_array('questions', $this->exportData))
		{
			$questionExportData = $this->getQuestionExportData();
			$xml = $insertData($questionExportData, 'Question');
		}

		if (in_array('logs', $this->exportData))
		{
			$logExportData = $this->getLogExportData();
			$xml = $insertData($logExportData, 'Log');
		}

		if (in_array('answers', $this->exportData))
		{
			$answerExportData = $this->getAnswerExportData();
			$xml = $insertData($answerExportData, 'Answer');
		}

		$document = new \DOMDocument();
		$document->preserveWhiteSpace = false;
		$document->formatOutput = true;
		$document->loadXML($xml->asXML());
		return $document;
	}

	public function exportToCsv()
	{
		$csv = $this->createCsv();

		$insertData = function (array $data, string $typeName) use ($csv) {
			if ($data)
			{
				$csv->insertOne([$typeName]);

				$typeHeaders = [];
				$typeValues = [];

				foreach ($data as $key => $value)
				{
					$typeHeaders = array_keys($value);
					$typeValues[] = $value;
				}

				$csv->insertOne($typeHeaders);
				$csv->insertAll($typeValues);
			}

			return $csv;
		};

		// Types
		if (in_array('types', $this->exportData))
		{
			$typeExportData = $this->getTypeExportData();
			$csv = $insertData($typeExportData, 'Types');
		}

		// Forms
		if (in_array('forms', $this->exportData))
		{
			$formExportData = $this->getFormExportData();
			$csv = $insertData($formExportData, 'Forms');
		}

		// Questions
		if (in_array('questions', $this->exportData))
		{
			$questionExportData = $this->getQuestionExportData();
			$csv = $insertData($questionExportData, 'Questions');
		}

		// Logs
		if (in_array('logs', $this->exportData))
		{
			$logExportData = $this->getLogExportData();
			$csv = $insertData($logExportData, 'Logs');
		}

		// Answers
		if (in_array('answers', $this->exportData))
		{
			$answerExportData = $this->getAnswerExportData();
			$csv = $insertData($answerExportData, 'Answers');
		}

		return $csv;
	}

	protected function getTypeExportData()
	{
		/** @var \Snog\Forms\Entity\Type[] $data */
		$data = $this->finder('Snog\Forms:Type')->order('appid', 'ASC')->fetch();

		$exportData = [];
		foreach ($data as $key => $value)
		{
			$exportData[] = $value->getExportData();
		}

		return $exportData;
	}

	protected function getFormExportData()
	{
		/** @var \Snog\Forms\Entity\Form[] $data */
		$data = $this->finder('Snog\Forms:Form')->order('posid', 'ASC')->fetch();

		$exportData = [];
		foreach ($data as $key => $value)
		{
			$exportData[] = $value->getExportData();
		}

		return $exportData;
	}

	protected function getQuestionExportData()
	{
		/** @var \Snog\Forms\Entity\Question[] $data */
		$data = $this->finder('Snog\Forms:Question')->order('questionid', 'ASC')->fetch();

		$exportData = [];
		foreach ($data as $key => $value)
		{
			$exportData[] = $value->getExportData();
		}

		return $exportData;
	}

	protected function getLogExportData()
	{
		/** @var \Snog\Forms\Entity\Log[] $data */
		$data = $this->finder('Snog\Forms:Log')->order('log_id', 'ASC')->fetch();

		$exportData = [];
		foreach ($data as $key => $value)
		{
			$exportData[] = $value->getExportData();
		}

		return $exportData;
	}

	protected function getAnswerExportData()
	{
		/** @var \Snog\Forms\Entity\Answers[] $data */
		$data = $this->finder('Snog\Forms:Answers')->order('answer_id', 'ASC')->fetch();

		$exportData = [];
		foreach ($data as $key => $value)
		{
			$exportData[] = $value->getExportData();
		}

		return $exportData;
	}

	/**
	 * @return \SimpleXMLElement
	 */
	protected function createXml()
	{
		$document = new \SimpleXMLElement('<?xml version = "1.0" encoding = "UTF-8"?><Forms_export></Forms_export>');
		return $document;
	}

	protected function createCsv()
	{
		$csv = \League\Csv\Writer::createFromString('');
		$csv->setDelimiter(';');
		$csv->setOutputBOM(\League\Csv\Writer::BOM_UTF8);

		return $csv;
	}
}