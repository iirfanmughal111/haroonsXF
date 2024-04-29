<?php

namespace Snog\Forms\ControllerPlugin;

use XF\ControllerPlugin\AbstractPlugin;
use XF\Mvc\Entity\Entity;
use XF\Service\Poll\Creator as PollCreatorSvc;

class Poll extends AbstractPlugin
{
	public function setupPollCreate($contentType, Entity $content, $form, $type)
	{
		/** @var PollCreatorSvc $creator */
		$creator = $this->service('XF:Poll\Creator', $contentType, $content);

		return $this->setupPollCreatorSvc(
			$creator,
			$form,
			$type
		);
	}

	public function setupPollCreatorSvc(PollCreatorSvc $pollCreatorSvc, $form, $type)
	{
		$pollInput = [];
		if ($type == 1)
		{
			$pollInput = $this->getPollInputYesNo($form);
		}
		if ($type == 2)
		{
			$pollInput = $this->getPollInputNormal($form);
		}

		$pollCreatorSvc->setQuestion($pollInput['question']);
		$pollCreatorSvc->setMaxVotes($pollInput['max_votes_type'], $pollInput['max_votes_value']);
		$pollCreatorSvc->setCloseDateRelative($pollInput['close_length'], $pollInput['close_units']);

		$pollCreatorSvc->setOptions([
			'change_vote' => $pollInput['change_vote'],
			'public_votes' => $pollInput['public_votes'],
			'view_results_unvoted' => $pollInput['view_results_unvoted']
		]);

		$pollCreatorSvc->addResponses($pollInput['new_responses']);

		return $pollCreatorSvc;
	}

	protected function getPollInputYesNo(\Snog\Forms\Entity\Form $form)
	{
		$input['poll'] = [
			'question' => $form->pollquestion,
			'existing_responses' => [],
			'new_responses' => [\XF::phrase('yes'), \XF::phrase('no')],
			'max_votes_type' => 'single',
			'max_votes_value' => 2,
			'close' => true,
			'remove_close' => false,
			'close_length' => $form->pollclose,
			'close_units' => 'days',
			'change_vote' => $form->pollchange,
			'public_votes' => $form->pollpublic,
			'view_results_unvoted' => $form->pollview
		];

		return $input['poll'];
	}

	protected function getPollInputNormal(\Snog\Forms\Entity\Form  $form)
	{
		$input['poll'] = [
			'question' => $form->normalquestion,
			'existing_responses' => [],
			'new_responses' => $form->response,
			'max_votes_type' => 'single',
			'max_votes_value' => 2,
			'close' => true,
			'remove_close' => false,
			'close_length' => $form->normalclose,
			'close_units' => 'days',
			'change_vote' => $form->normalchange,
			'public_votes' => $form->normalpublic,
			'view_results_unvoted' => $form->normalview
		];

		return $input['poll'];
	}
}