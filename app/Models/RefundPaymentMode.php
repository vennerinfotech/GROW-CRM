<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundPaymentMode extends Model
{
    protected $table = 'refund_payment_modes';
    protected $primaryKey = 'refundpaymentmode_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['refundpaymentmode_id'];

    const CREATED_AT = 'refundpaymentmode_created';
    const UPDATED_AT = 'refundpaymentmode_updated';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'refundpaymentmode_created' => 'datetime',
        'refundpaymentmode_updated' => 'datetime',
    ];

    /**
     * Relationship: Payment Mode has many Refunds
     */
    public function refunds()
    {
        return $this->hasMany('App\Models\Refund', 'refund_payment_modeid', 'refundpaymentmode_id');
    }
}
