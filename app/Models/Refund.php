<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */
    protected $table = 'refunds';

    protected $primaryKey = 'refund_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['refund_id'];

    const CREATED_AT = 'refund_created';
    const UPDATED_AT = 'refund_updated';

    /**
     * Relationship: Refund belongs to a Status
     */
    public function status()
    {
        return $this->belongsTo('App\Models\RefundStatus', 'refund_statusid', 'refundstatus_id');
    }

    /**
     * Relationship: Refund belongs to a Payment Mode
     */
    public function payment_mode()
    {
        return $this->belongsTo('App\Models\RefundPaymentMode', 'refund_payment_modeid', 'refundpaymentmode_id');
    }

    /**
     * Relationship: Refund created by a User
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'refund_creatorid', 'id');
    }

    /**
     * Relationship: Error By User
     */
    public function error_by()
    {
        return $this->belongsTo('App\Models\User', 'refund_error_by_userid', 'id');
    }

    /** Relationship: Sales By User */

    /**
     * Relationship: Sales By User
     */
    public function sales_by()
    {
        return $this->belongsTo('App\Models\User', 'refund_sales_by_userid', 'id');
    }

    /**
     * Relationship: Refund has one Reason
     */
    public function reason()
    {
        return $this->belongsTo('App\Models\RefundReason', 'refund_reasonid', 'refundreason_id');
    }

    /**
     * Relationship: Refund has one Courier
     */
    public function courier()
    {
        return $this->belongsTo('App\Models\RefundCourier', 'refund_courierid', 'refundcourier_id');
    }

    /**
     * Relationship: Error Source
     */
    public function error_source()
    {
        return $this->belongsTo('App\Models\RefundErrorSource', 'refund_error_sourceid', 'refunderrorsource_id');
    }

    /**
     * Relationship: Sales Source
     */
    public function sales_source()
    {
        return $this->belongsTo('App\Models\RefundSalesSource', 'refund_sales_sourceid', 'refundsalessource_id');
    }
}
