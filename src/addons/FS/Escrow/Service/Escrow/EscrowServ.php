<?php

namespace FS\Escrow\Service\Escrow;

use XF\Mvc\FormAction;

class EscrowServ extends \XF\Service\AbstractService
{
    public function escrowTransaction($user_id, $amount, $current_amt, $type, $escrow_id)
    {
        $transaction = $this->em()->create('FS\Escrow:Transaction');

        $transaction->user_id = $user_id;
        $transaction->transaction_amount = $amount;
        $transaction->current_amount = $current_amt;
        $transaction->transaction_type = $type;
        $transaction->escrow_id = $escrow_id;

        $transaction->save();

        return $transaction;
    }
}
