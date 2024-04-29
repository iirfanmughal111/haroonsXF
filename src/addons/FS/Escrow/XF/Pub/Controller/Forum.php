<?php

namespace FS\Escrow\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum
{
    public function actionIndex(ParameterBag $params)
    {
        if ($params->node_id == intval($this->app()->options()->fs_escrow_applicable_forum)) {

            return $this->redirect($this->buildLink('escrow/'), '');
        }

        return parent::actionIndex($params);
    }

    protected function setupThreadCreate(\XF\Entity\Forum $forum)
    {
        $parent = parent::setupThreadCreate($forum);

        $visitor = \XF::visitor();

        $totalAmount = $this->filter('escrow_amount', 'uint') + ((intval($this->app()->options()->fs_escrow_admin_percentage) / 100) * $this->filter('escrow_amount', 'uint'));

        if ($forum->node_id ==  intval($this->app()->options()->fs_escrow_applicable_forum) && $visitor->deposit_amount < $totalAmount) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_enough_amount"))
            );
        }

        $options = $this->app()->options();

        if ($forum->node_id ==  intval($options->fs_escrow_applicable_forum)) {

            $inputs = $this->filter([
                'to_user' => 'str',
                'escrow_amount' => 'int',
            ]);

            if (!$inputs['to_user'] || $inputs['escrow_amount'] < 1) {
                throw $this->exception(
                    $this->error(\XF::phrase("fs_escrow_enter_valid_data"))
                );
            }

            if ($this->filter('to_user', 'str') == $visitor->username) {
                throw $this->exception(
                    $this->error(\XF::phrase("fs_escrow_cant_mention_own_name"))
                );
            }

            $user = $this->em()->findOne('XF:User', ['username' => $inputs['to_user']]);

            if (!$user) {
                throw $this->exception(
                    $this->error(\XF::phrase("fs_escrow_user_not_found"))
                );
            }

            $visitor->fastUpdate('deposit_amount', ($visitor->deposit_amount - $totalAmount));

            $escrowService = \xf::app()->service('FS\Escrow:Escrow\EscrowServ');

            $transaction = $escrowService->escrowTransaction($visitor->user_id, ($this->filter('escrow_amount', 'uint') + ((intval($this->app()->options()->fs_escrow_admin_percentage) / 100) * $this->filter('escrow_amount', 'uint'))), $visitor->deposit_amount, 'Freeze', 0);

            $escrowRecord = $this->em()->create('FS\Escrow:Escrow');

            $escrowRecord->to_user = $user->user_id;
            $escrowRecord->user_id = $visitor->user_id;
            $escrowRecord->escrow_amount = $inputs['escrow_amount'];
            $escrowRecord->transaction_id = $transaction->transaction_id;
            $escrowRecord->admin_percentage = intval($this->app()->options()->fs_escrow_admin_percentage);

            $escrowRecord->save();

            $transaction->fastUpdate('escrow_id', $escrowRecord->escrow_id);

            $parent->setEscrowId($escrowRecord->escrow_id);
        }

        return $parent;
    }

    protected function finalizeThreadCreate(\XF\Service\Thread\Creator $creator)
    {
        $parent = parent::finalizeThreadCreate($creator);

        $thread = $creator->getThread();

        $options = $this->app()->options();

        if ($thread->node_id ==  intval($options->fs_escrow_applicable_forum)) {

            $escrow = $this->em()->findOne('FS\Escrow:Escrow', ['escrow_id' => $thread['escrow_id']]);
            $user = $this->em()->findOne('XF:User', ['user_id' => $escrow['to_user']]);

            // $newState = 'watch_email';

            $newState = 'watch_no_email';

            /** @var \XF\Repository\ThreadWatch $watchRepo */
            $watchRepo = $this->repository('XF:ThreadWatch');
            $watchRepo->setWatchState($thread, $user, $newState);

            $escrow->fastUpdate('thread_id', $thread->thread_id);

            $thread->fastUpdate('discussion_state', 'visible');
        }

        return $parent;
    }
}
