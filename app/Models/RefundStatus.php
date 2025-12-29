<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundStatus extends Model
{
    protected $table = 'refund_statuses';
    protected $primaryKey = 'refundstatus_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['refundstatus_id'];

    const CREATED_AT = 'refundstatus_created';
    const UPDATED_AT = 'refundstatus_updated';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'refundstatus_created' => 'datetime',
        'refundstatus_updated' => 'datetime',
    ];

    /**
     * Relationship: Status has many Refunds
     */
    public function refunds()
    {
        return $this->hasMany('App\Models\Refund', 'refund_statusid', 'refundstatus_id');
    }
}
