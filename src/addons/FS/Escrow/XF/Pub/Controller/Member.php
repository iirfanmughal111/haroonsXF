<?php

namespace FS\Escrow\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Member extends XFCP_Member
{
    public function actionMyEscrow(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        if ($visitor->user_id == 0) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }
        $user = $this->assertViewableUser($params->user_id);
        $finder = $this->finder('FS\Escrow:Escrow')->where('thread_id', '!=', 0)->where('user_id', $user->user_id);

        $maxItems = 25;
        $beforeId = $this->filter('before_id', 'uint');
        if (isset($beforeId) && $beforeId != null) {
            $finder->where('escrow_id', '<', $beforeId);
        }

        $items = $finder->order('escrow_id', 'desc')->fetch($maxItems);

        $items = $items->slice(0, $maxItems);

        $lastItem = $items->last();

        $oldestItemId = $lastItem ? $lastItem->escrow_id : 0;

        $viewParams = [
            'escrows' => $items,
            'oldestItemId' => $oldestItemId,
            'beforeId' => $beforeId,
            'type' => 'my',
            'user' => $user

        ];


        return $this->view('FS\Escrow', 'fs_escrow_escrow_list', $viewParams);
    }

    public function actionMentionedEscrow(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        if ($visitor->user_id == 0) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }
        $user = $this->assertViewableUser($params->user_id);
        $finder = $this->finder('FS\Escrow:Escrow')->where('thread_id', '!=', 0)->where('to_user', $user->user_id);

        $maxItems = 25;
        $beforeId = $this->filter('before_id', 'uint');
        if (isset($beforeId) && $beforeId != null) {
            $finder->where('escrow_id', '<', $beforeId);
        }

        $items = $finder->order('escrow_id', 'desc')->fetch($maxItems);

        $items = $items->slice(0, $maxItems);

        $lastItem = $items->last();

        $oldestItemId = $lastItem ? $lastItem->escrow_id : 0;
        $viewParams = [
            'escrows' => $items,
            'oldestItemId' => $oldestItemId,
            'beforeId' => $beforeId,
            'type' => 'mentioned',
            'user' => $user

        ];

        return $this->view('FS\Escrow', 'fs_escrow_escrow_list', $viewParams);
    }
    public function actionLogs(ParameterBag $params)
    {
        $user = $this->assertViewableUser($params->user_id);

        $visitor = \XF::visitor();
        if ($visitor->user_id == 0) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }

        $finder = $this->finder('FS\Escrow:Transaction')->where('user_id', $user->user_id);

        $maxItems = 50;
        $beforeId = $this->filter('before_id', 'uint');
        if (isset($beforeId) && $beforeId != null) {
            $finder->where('transaction_id', '<', $beforeId);
        }

        $logs = $finder->order('transaction_id', 'desc')->fetch($maxItems);
        $logs = $logs->slice(0, $maxItems);

        $lastItem = $logs->last();

        $oldestItemId = $lastItem ? $lastItem->transaction_id : 0;
        $viewParams = [
            'logs' => $logs,
            'oldestItemId' => $oldestItemId,
            'beforeId' => $beforeId,
            'type' => 'mentioned',
            'user' => $user

        ];

        return $this->view('FS\Escrow', 'fs_escrow_logs', $viewParams);
    }
}
