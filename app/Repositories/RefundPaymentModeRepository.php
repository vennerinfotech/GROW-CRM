<?php

namespace App\Repositories;

use App\Models\RefundPaymentMode;
use Log;

class RefundPaymentModeRepository
{
    protected $mode;

    public function __construct(RefundPaymentMode $mode)
    {
        $this->mode = $mode;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object
     */
    public function search($id = '')
    {
        $mode = $this->mode->newQuery();

        $mode->selectRaw('*');

        if (is_numeric($id)) {
            $mode->where('refundpaymentmode_id', $id);
        }

        // default sorting
        $mode->orderBy('refundpaymentmode_title', 'asc');

        return $mode->paginate(10000);
    }

    /**
     * update a record
     * @param int $id record id
     * @return mixed bool or id of record
     */
    public function update($id)
    {
        if (!$mode = $this->mode->find($id)) {
            return false;
        }

        $mode->refundpaymentmode_title = request('refundpaymentmode_title');

        if ($mode->save()) {
            return $mode->refundpaymentmode_id;
        } else {
            return false;
        }
    }

    /**
     * Create a new record
     * @return mixed object|bool
     */
    public function create()
    {
        $mode = new $this->mode;

        $mode->refundpaymentmode_title = request('refundpaymentmode_title');

        if ($mode->save()) {
            return $mode->refundpaymentmode_id;
        } else {
            return false;
        }
    }
}
