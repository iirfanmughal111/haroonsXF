<?php


namespace Snog\Forms\Service;


class Import extends \XF\Service\AbstractService
{
	protected $newPosIds = [];
	protected $newAppIds = [];
	protected $newQuestionIds = [];

	protected $doMerge = false;

	protected $db;

	public function __construct(\XF\App $app)
	{
		parent::__construct($app);

		$this->db = $app->db();
	}

	public function setDoMerge($merge)
	{
		$this->doMerge = $merge;
	}

	public function importXml($xml)
	{
		if (function_exists('set_time_limit')) @set_time_limit(0);

		if (!$this->doMerge)
		{
			$this->deleteAllImportData();
		}

		foreach ($xml->children() as $child)
		{
			$levelName = $child->getName();

			if ($levelName == 'Type')
			{
				$importData = $this->getTypeImportData($child->children());
				$this->db->insertBulk('xf_snog_forms_types', $importData);
			}
			elseif ($levelName == 'Form')
			{
				$importData = $this->getFormImportData($child->children());
				$this->db->insertBulk('xf_snog_forms_forms', $importData);
			}
			elseif ($levelName == 'Question')
			{
				$importData = $this->getQuestionImportData($child->children());
				$this->db->insertBulk('xf_snog_forms_questions', $importData);
			}
		}

		$this->rebuildConditionals();
	}

	public function getTypeImportData($items)
	{
		$jsonArrays = [
			'user_criteria'
		];

		$data = [];
		$oldAppId = '';

		foreach ($items as $item)
		{
			$name = $item->getName();

			if (!in_array($name, $jsonArrays))
			{
				// SET OLD VALUE OF TYPE APPID
				if ($name == 'appid')
				{
					$oldAppId = $item;
				}

				if ($name !== 'appid')
				{
					$data[$name] = html_entity_decode($item);
				}
			}
			else
			{
				// CONVERT TO JSON ARRAY
				$tempItem = html_entity_decode($item);
				$tempArray = \XF\Util\Php::safeUnserialize($tempItem);
				$data[$name] = json_encode($tempArray);
			}
		}

		$this->newAppIds[] = ['oldid' => $oldAppId, 'newid' => $this->db->lastInsertId()];

		return [$data];
	}

	protected function getFormImportData($items)
	{
		$data = [];

		$jsonArrays = [
			'addto',
			'pollpromote',
			'user_criteria',
			'response',
			'forummod',
			'supermod',
			'qrforums'
		];

		$oldPosId = 0;

		foreach ($items as $item)
		{
			$name = $item->getName();

			// SET OLD VALUE OF FORM POSID
			if ($name == 'posid') $oldPosId = $item;

			if ($name !== 'posid')
			{
				if (!in_array($name, $jsonArrays))
				{
					if ($name == 'appid')
					{
						// USE NEW APPID
						$oldid = $item;
						foreach ($this->newAppIds as $newAppId)
						{
							if (intval($oldid) == intval($newAppId['oldid']))
							{
								$item = $newAppId['newid'];
								break;
							}
						}
					}
					// Remap old
					elseif ($name == 'prefix_id')
					{
						$name = 'prefix_ids';
					}

					$data[$name] = html_entity_decode($item);
				}
				else
				{
					// CONVERT TO JSON ARRAY
					$tempItem = html_entity_decode($item);
					$tempArray = \XF\Util\Php::safeUnserialize($tempItem);
					$data[$name] = json_encode($tempArray);
				}
			}
		}

		$this->newPosIds[] = ['oldid' => $oldPosId, 'newid' => $this->db->lastInsertId()];

		return [$data];
	}

	protected function getQuestionImportData($items)
	{
		$data = [];

		$jsonArrays = [
			'hasconditional'
		];

		$oldQuestionId = 0;

		foreach ($items as $item)
		{
			$name = $item->getName();

			if ($name == 'questionid')
			{
				$oldQuestionId = intval($item);
			}

			if ($name <> 'questionid')
			{
				if (!in_array($name, $jsonArrays))
				{
					if ($name == 'posid')
					{
						// USE NEW POSID
						$oldid = $item;
						foreach ($this->newPosIds as $newPosId)
						{
							if (intval($oldid) == intval($newPosId['oldid']))
							{
								$item = $newPosId['newid'];
								break;
							}
						}
					}

					// USE NEW PARENT QUESTION ID
					if ($name == 'conditional')
					{
						foreach ($this->newQuestionIds as $newQid)
						{
							if ($item == $newQid['oldid'])
							{
								$item = $newQid['newid'];
								break;
							}
						}
					}

					$data[$name] = html_entity_decode($item);
				}
				else
				{
					// CONVERT TO JSON ARRAY
					$tempitem = html_entity_decode($item);
					$temparray = \XF\Util\Php::safeUnserialize($tempitem);
					$data[$name] = json_encode($temparray);
				}
			}
		}

		$newId = $this->db->lastInsertId();

		if ($oldQuestionId !== $newId)
			$this->newQuestionIds[] = ['oldid' => $oldQuestionId, 'newid' => $newId];

		return [$data];
	}

	protected function rebuildConditionals()
	{
		/** @var \Snog\Forms\Entity\Question[] $questions */
		$questions = $this->finder('Snog\Forms:Question')
			->where('hasconditional', 'NOT LIKE', '[]')
			->fetch();

		foreach ($questions as $question)
		{
			$conditionArray = [];

			if (!empty($question->hasconditional))
			{
				/** @var \Snog\Forms\Entity\Question[] $conditionalQuestions */
				$conditionalQuestions = $this->finder('Snog\Forms:Question')
					->where('conditional', $question->questionid)
					->fetch();

				if ($conditionalQuestions)
				{
					foreach ($conditionalQuestions as $conditionalQuestion)
					{
						$conditionArray[] = $conditionalQuestion->questionid;
					}

					$question->hasconditional = $conditionArray;
					$question->save();
				}
			}
		}
	}

	protected function deleteAllImportData()
	{
		/** @var \Snog\Forms\Repository\Type $typeRepo */
		$typeRepo = $this->repository('Snog\Forms:Type');
		$typeRepo->deleteAllTypes();

		/** @var \Snog\Forms\Repository\Form $formRepo */
		$formRepo = $this->repository('Snog\Forms:Form');
		$formRepo->deleteAllForms();

		/** @var \Snog\Forms\Repository\Question $questionRepo */
		$questionRepo = $this->repository('Snog\Forms:Question');
		$questionRepo->deleteAllQuestions();
	}
}