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
        $refunds->leftJoin('refund_reasons', 'refund_reasons.refundreason_id', '=', 'refunds.refund_reasonid');
        $refunds->leftJoin('refund_couriers', 'refund_couriers.refundcourier_id', '=', 'refunds.refund_courierid');

        // select all
        $refunds->selectRaw('refunds.*, refund_statuses.refundstatus_title, refund_statuses.refundstatus_color, refund_payment_modes.refundpaymentmode_title');
        $refunds->selectRaw('creator.first_name as creator_first_name, creator.last_name as creator_last_name');
        $refunds->selectRaw('refund_error_sources.refunderrorsource_title');
        $refunds->selectRaw('refund_sales_sources.refundsalessource_title');
        $refunds->selectRaw('refund_reasons.refundreason_title as refund_reason');
        $refunds->selectRaw('refund_couriers.refundcourier_title as refund_courier');

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

        if (request()->filled('search_query') || request()->filled('query')) {
            $refunds->where(function ($query) {
                $query->orWhere('refund_id', '=', request('search_query'));
                $query->orWhere('refund_bill_no', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('refund_amount', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('refund_docket_no', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('refund_authorized_description', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('refund_rejected_reason', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhereDate('refund_created', '=', date('Y-m-d', strtotime(request('search_query'))));

                // Search by Status
                $query->orWhereHas('status', function ($q) {
                    $q->where('refundstatus_title', 'LIKE', '%' . request('search_query') . '%');
                });

                // Search by Creator
                $query->orWhereHas('creator', function ($q) {
                    $q->where('first_name', 'LIKE', '%' . request('search_query') . '%');
                    $q->where('last_name', 'LIKE', '%' . request('search_query') . '%');
                });

                // Search by Payment Mode
                $query->orWhereHas('payment_mode', function ($q) {
                    $q->where('refundpaymentmode_title', 'LIKE', '%' . request('search_query') . '%');
                });

                // Search by Reason
                $query->orWhereHas('reason', function ($q) {
                    $q->where('refundreason_title', 'LIKE', '%' . request('search_query') . '%');
                });

                // Search by Courier
                $query->orWhereHas('courier', function ($q) {
                    $q->where('refundcourier_title', 'LIKE', '%' . request('search_query') . '%');
                });

                // Search by Error Source
                $query->orWhereHas('error_source', function ($q) {
                    $q->where('refunderrorsource_title', 'LIKE', '%' . request('search_query') . '%');
                });

                // Search by Sales Source
                $query->orWhereHas('sales_source', function ($q) {
                    $q->where('refundsalessource_title', 'LIKE', '%' . request('search_query') . '%');
                });
            });
        }

        // return query
        if ($id == 'all') {
            return $refunds->get();
        } else {
            return $refunds->paginate(config('system.settings_system_pagination_limits'));
        }
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
        $refund->refund_reasonid = request('refund_reasonid');
        $refund->refund_courierid = request('refund_courierid');
        $refund->refund_docket_no = request('refund_docket_no');
        $refund->refund_payment_modeid = request('refund_payment_modeid');
        $refund->refund_statusid = request('refund_statusid');
        $refund->refund_error_sourceid = request('refund_error_sourceid');
        $refund->refund_sales_sourceid = request('refund_sales_sourceid');
        $refund->refund_authorized_date = request('refund_authorized_date');
        $refund->refund_payment_date = request('refund_payment_date');
        $refund->refund_authorized_description = request('refund_authorized_description');
        $refund->refund_rejected_reason = request('refund_rejected_reason');
        $refund->refund_creatorid = auth()->id();

        // Image Upload
        if (request()->hasFile('refund_image')) {
            $path = request()->file('refund_image')->store('files', 'public');
            $refund->refund_image = basename($path);
        } elseif (request()->filled('refund_image')) {
            $refund->refund_image = request('refund_image');
        }

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
        $refund->refund_reasonid = request('refund_reasonid');
        $refund->refund_courierid = request('refund_courierid');
        $refund->refund_docket_no = request('refund_docket_no');
        $refund->refund_payment_modeid = request('refund_payment_modeid');
        $refund->refund_statusid = request('refund_statusid');
        $refund->refund_error_sourceid = request('refund_error_sourceid');
        $refund->refund_sales_sourceid = request('refund_sales_sourceid');
        $refund->refund_authorized_date = request('refund_authorized_date');
        $refund->refund_payment_date = request('refund_payment_date');
        $refund->refund_authorized_description = request('refund_authorized_description');
        $refund->refund_rejected_reason = request('refund_rejected_reason');

        // Image Upload
        if (request()->hasFile('refund_image')) {
            $path = request()->file('refund_image')->store('files', 'public');
            $refund->refund_image = basename($path);
        } elseif (request()->filled('refund_image')) {
            $refund->refund_image = request('refund_image');
        }

        if ($refund->save()) {
            return $refund->refund_id;
        } else {
            return false;
        }
    }

    /**
     * Get refund statistics
     * @param string $period [today|month|all]
     * @return array
     */
    public function getStatistics($period = 'all')
    {
        $query = $this->refunds->newQuery();

        if ($period == 'today') {
            $query->whereDate('refund_created', \Carbon\Carbon::now()->format('Y-m-d'));
        } elseif ($period == 'month') {
            $query
                ->whereDate('refund_created', '>=', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'))
                ->whereDate('refund_created', '<=', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));
        }

        return [
            'count' => $query->count(),
            'sum' => $query->sum('refund_amount'),
        ];
    }

    /**
     * Group refunds by status
     * @return collection
     */
    public function groupByStatus()
    {
        // statuses to include
        $statuses = [
            1 => 'Initiate',  // Initial/New/Initiate
            2 => 'Authorized',
            3 => 'Completed',
            5 => 'Rejected'
        ];

        $stats = collect();

        foreach ($statuses as $id => $title) {
            $count = $this->refunds->newQuery()->where('refund_statusid', $id)->count();
            $sum = $this->refunds->newQuery()->where('refund_statusid', $id)->sum('refund_amount');

            // Map status ID to color (hardcoded for now based on standard logic or could fetch from DB)
            $color = match ($id) {
                1 => 'info',
                2 => 'primary',
                3 => 'success',
                5 => 'danger',
                default => 'default'
            };

            $stats->push((object) [
                'count' => $count,
                'sum' => $sum,
                'title' => $title,
                'color' => $color,
                'percentage' => 0  // Placeholder, if needed calculate percentage here or remove
            ]);
        }

        return $stats;
    }

    /**
     * Group refunds by payment mode
     * @return collection
     */
    public function groupByPaymentMode()
    {
        return $this
            ->refunds
            ->newQuery()
            ->join('refund_payment_modes', 'refund_payment_modes.refundpaymentmode_id', '=', 'refunds.refund_payment_modeid')
            ->selectRaw('count(*) as count, sum(refund_amount) as sum, refund_payment_modes.refundpaymentmode_title as title')
            ->groupBy('refund_payment_modeid')
            ->get();
    }
}
