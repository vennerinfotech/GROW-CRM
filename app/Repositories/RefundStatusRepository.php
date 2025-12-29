<?php

namespace App\Repositories;

use App\Models\RefundStatus;
use Log;

class RefundStatusRepository
{
    protected $status;

    public function __construct(RefundStatus $status)
    {
        $this->status = $status;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object refund status collection
     */
    public function search($id = '')
    {
        $status = $this->status->newQuery();

        // all fields
        $status->selectRaw('*');

        // count refunds
        $status->selectRaw('(SELECT COUNT(*)
                                      FROM refunds
                                      WHERE refund_statusid = refund_statuses.refundstatus_id)
                                      AS count_refunds');
        if (is_numeric($id)) {
            $status->where('refundstatus_id', $id);
        }

        // default sorting
        $status->orderBy('refundstatus_position', 'asc');

        return $status->paginate(10000);
    }

    /**
     * update a record
     * @param int $id record id
     * @return mixed bool or id of record
     */
    public function update($id)
    {
        if (!$status = $this->status->find($id)) {
            return false;
        }

        // general
        $status->refundstatus_title = request('refundstatus_title');
        $status->refundstatus_color = request('refundstatus_color');

        if ($status->save()) {
            return $status->refundstatus_id;
        } else {
            return false;
        }
    }

    /**
     * Create a new record
     * @param int $position position of new record
     * @return mixed object|bool
     */
    public function create($position = '')
    {
        // validate
        if (!is_numeric($position)) {
            return false;
        }

        $status = new $this->status;

        $status->refundstatus_title = request('refundstatus_title');
        $status->refundstatus_color = request('refundstatus_color');
        $status->refundstatus_position = $position;

        if ($status->save()) {
            return $status->refundstatus_id;
        } else {
            return false;
        }
    }
}
