<?php

namespace App\Repositories;

use App\Models\Refund;
use Illuminate\Http\Request;
use Log;

class RefundRepository
{
    protected $refunds;

    public function __construct(Refund $refunds)
    {
        $this->refunds = $refunds;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object refund collection
     */
    public function search($id = '')
    {
        $refunds = $this->refunds->newQuery();

        // joins
        $refunds->leftJoin('refund_statuses', 'refund_statuses.refundstatus_id', '=', 'refunds.refund_statusid');
        $refunds->leftJoin('refund_payment_modes', 'refund_payment_modes.refundpaymentmode_id', '=', 'refunds.refund_payment_modeid');
        $refunds->leftJoin('users as creator', 'creator.id', '=', 'refunds.refund_creatorid');
        $refunds->leftJoin('refund_error_sources', 'refund_error_sources.refunderrorsource_id', '=', 'refunds.refund_error_sourceid');
        $refunds->leftJoin('refund_sales_sources', 'refund_sales_sources.refundsalessource_id', '=', 'refunds.refund_sales_sourceid');

        // select all
        $refunds->selectRaw('refunds.*, refund_statuses.refundstatus_title, refund_statuses.refundstatus_color, refund_payment_modes.refundpaymentmode_title');
        $refunds->selectRaw('creator.first_name as creator_first_name, creator.last_name as creator_last_name');
        $refunds->selectRaw('refund_error_sources.refunderrorsource_title');
        $refunds->selectRaw('refund_sales_sources.refundsalessource_title');

        // default sorting
        $refunds->orderBy('refund_id', 'desc');

        if (is_numeric($id)) {
            $refunds->where('refund_id', $id);
        }

        // Example filter logic implementation (basic)
        if (request()->filled('filter_refund_bill_no')) {
            $refunds->where('refund_bill_no', 'LIKE', '%' . request('filter_refund_bill_no') . '%');
        }

        if (request()->filled('filter_refund_created_start')) {
            $refunds->whereDate('refund_created', '>=', request('filter_refund_created_start'));
        }

        if (request()->filled('filter_refund_created_end')) {
            $refunds->whereDate('refund_created', '<=', request('filter_refund_created_end'));
        }

        if (request()->filled('filter_refund_amount_min')) {
            $refunds->where('refund_amount', '>=', request('filter_refund_amount_min'));
        }

        if (request()->filled('filter_refund_amount_max')) {
            $refunds->where('refund_amount', '<=', request('filter_refund_amount_max'));
        }

        if (request()->filled('filter_refund_statusid') && is_array(request('filter_refund_statusid'))) {
            $refunds->whereIn('refund_statusid', request('filter_refund_statusid'));
        }

        if (request()->filled('filter_refund_payment_modeid') && is_array(request('filter_refund_payment_modeid'))) {
            $refunds->whereIn('refund_payment_modeid', request('filter_refund_payment_modeid'));
        }

        if (request()->filled('filter_refund_error_sourceid') && is_array(request('filter_refund_error_sourceid'))) {
            $refunds->whereIn('refund_error_sourceid', request('filter_refund_error_sourceid'));
        }

        if (request()->filled('filter_refund_sales_sourceid') && is_array(request('filter_refund_sales_sourceid'))) {
            $refunds->whereIn('refund_sales_sourceid', request('filter_refund_sales_sourceid'));
        }

        // return query
        return $refunds->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Create a new record
     * @return mixed int|bool
     */
    public function create()
    {
        $refund = new $this->refunds;

        $refund->refund_bill_no = request('refund_bill_no');
        $refund->refund_amount = request('refund_amount');
        $refund->refund_reason = request('refund_reason');
        $refund->refund_courier = request('refund_courier');
        $refund->refund_docket_no = request('refund_docket_no');
        $refund->refund_payment_modeid = request('refund_payment_modeid');
        $refund->refund_statusid = request('refund_statusid');
        $refund->refund_error_sourceid = request('refund_error_sourceid');
        $refund->refund_sales_sourceid = request('refund_sales_sourceid');
        $refund->refund_creatorid = auth()->id();

        if ($refund->save()) {
            return $refund->refund_id;
        } else {
            return false;
        }
    }

    /**
     * update a record
     * @param int $id record id
     * @return mixed int|bool
     */
    public function update($id)
    {
        if (!$refund = $this->refunds->find($id)) {
            return false;
        }

        $refund->refund_bill_no = request('refund_bill_no');
        $refund->refund_amount = request('refund_amount');
        $refund->refund_reason = request('refund_reason');
        $refund->refund_courier = request('refund_courier');
        $refund->refund_docket_no = request('refund_docket_no');
        $refund->refund_payment_modeid = request('refund_payment_modeid');
        $refund->refund_statusid = request('refund_statusid');
        $refund->refund_error_sourceid = request('refund_error_sourceid');
        $refund->refund_sales_sourceid = request('refund_sales_sourceid');

        if ($refund->save()) {
            return $refund->refund_id;
        } else {
            return false;
        }
    }
}
