<?php

namespace Snog\Forms\XF\Admin\Controller;

use XF\Mvc\ParameterBag;

class Log extends XFCP_Log
{
	public function actionFormsLogs(ParameterBag $params)
	{
		if ($params->log_id)
		{
			$entry = $this->assertFormsLogExists($params->log_id);

			/** @var \Snog\Forms\Repository\Answer $answerRepo */
			$answerRepo = $this->repository('Snog\Forms:Answer');
			$answers = $answerRepo->findAnswers()->with('Question')->byLog($entry->log_id)->fetch();

			$viewParams = [
				'entry' => $entry,
				'answers' => $answers
			];
			return $this->view('XF:Log\FormsLogs\View', 'snog_forms_log_view', $viewParams);
		}
		else
		{
			$page = $this->filterPage();
			$perPage = 20;

			$repo = $this->getFormsLogRepo();
			$finder = $repo->findLogs()
				->with(['User', 'Form'])
				->orderByDate()
				->limitByPage($page, $perPage);

			$viewParams = [
				'entries' => $finder->fetch(),
				'page' => $page,
				'perPage' => $perPage,
				'total' => $finder->total()
			];
			return $this->view('XF:Log\FormsLogs\Listing', 'snog_forms_log_list', $viewParams);
		}
	}

	public function actionFormsLogsDelete(ParameterBag $params)
	{
		$entry = $this->assertFormsLogExists($params->log_id);

		/** @var \XF\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('XF:Delete');
		return $plugin->actionDelete(
			$entry,
			$this->buildLink('logs/forms-logs/delete', $entry),
			$this->buildLink('logs/forms-logs', $entry),
			$this->buildLink('logs/forms-logs'),
			\XF::phrase('snog_forms_log_by_x_from_x', [
				'log_id' => $entry->log_id,
				'username' => $entry->User ? $entry->User->username : \XF\Util\Ip::convertIpBinaryToString($entry->ip_address),
				'time' => \XF::language()->dateTime($entry->log_date)
			])
		);
	}

	public function actionFormsLogsClear()
	{
		if ($this->isPost())
		{
			if ($this->filter('clear_answers', 'bool'))
			{
				/** @var \Snog\Forms\Repository\Answer $answerRepo */
				$answerRepo = $this->repository('Snog\Forms:Answer');
				$answerRepo->clearAnswers();
			}
			$this->getFormsLogRepo()->clearLog();
			return $this->redirect($this->buildLink('logs/forms-logs/'));
		}
		else
		{
			return $this->view('XF:Log\FormsLogs\Clear', 'snog_forms_log_clear');
		}
	}

	/**
	 * @param string $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \Snog\Forms\Entity\Log|\XF\Mvc\Entity\Entity
	 * @throws \Exception
	 */
	protected function assertFormsLogExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('Snog\Forms:Log', $id, $with, $phraseKey);
	}

	/**
	 * @return \Snog\Forms\Repository\Log|\XF\Mvc\Entity\Repository
	 */
	protected function getFormsLogRepo()
	{
		return $this->repository('Snog\Forms:Log');
	}
}