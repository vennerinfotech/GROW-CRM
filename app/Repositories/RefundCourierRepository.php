<?php

namespace App\Repositories;

use App\Models\RefundCourier;
use Illuminate\Http\Request;

class RefundCourierRepository
{
    /**
     * The leads repository instance.
     */
    protected $refundcourier;

    /**
     * Inject the models.
     */
    public function __construct(RefundCourier $refundcourier)
    {
        $this->refundcourier = $refundcourier;
    }

    /**
     * Search model
     * @return object collection
     */
    public function search()
    {
        $refundcouriers = $this->refundcourier->newQuery();

        // all defaults
        $refundcouriers->select('*');

        // default sorting
        $refundcouriers->orderBy('refundcourier_id', 'desc');

        return $refundcouriers->get();
    }

    /**
     * Create a new record
     * @return mixed int|bool
     */
    public function create()
    {
        // save
        $refundcourier = new $this->refundcourier;

        // data
        $refundcourier->refundcourier_title = request('refundcourier_title');

        // save
        if ($refundcourier->save()) {
            return $refundcourier->refundcourier_id;
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
        if (!$refundcourier = $this->refundcourier->find($id)) {
            return false;
        }

        // general
        $refundcourier->refundcourier_title = request('refundcourier_title');

        // save
        if ($refundcourier->save()) {
            return true;
        } else {
            return false;
        }
    }
}
