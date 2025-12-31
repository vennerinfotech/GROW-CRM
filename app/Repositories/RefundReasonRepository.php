<?php

namespace App\Repositories;

use App\Models\RefundReason;
use Illuminate\Http\Request;

class RefundReasonRepository
{
    /**
     * The leads repository instance.
     */
    protected $refundreason;

    /**
     * Inject the models.
     */
    public function __construct(RefundReason $refundreason)
    {
        $this->refundreason = $refundreason;
    }

    /**
     * Search model
     * @return object collection
     */
    public function search()
    {
        $refundreasons = $this->refundreason->newQuery();

        // all defaults
        $refundreasons->select('*');

        // default sorting
        $refundreasons->orderBy('refundreason_id', 'desc');

        return $refundreasons->get();
    }

    /**
     * Create a new record
     * @return mixed int|bool
     */
    public function create()
    {
        // save
        $refundreason = new $this->refundreason;

        // data
        $refundreason->refundreason_title = request('refundreason_title');

        // save
        if ($refundreason->save()) {
            return $refundreason->refundreason_id;
        } else {
            return false;
        }
    }

    /**
     * Update a record
     * @param int $id record id
     * @return bool
     */
    public function update($id)
    {
        // get the record
        if (!$refundreason = $this->refundreason->find($id)) {
            return false;
        }

        // general
        $refundreason->refundreason_title = request('refundreason_title');

        // save
        if ($refundreason->save()) {
            return true;
        } else {
            return false;
        }
    }
}
