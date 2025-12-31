<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundCourier extends Model
{
    /**
     * @var string
     */
    protected $table = 'refund_couriers';

    /**
     * @var string
     */
    protected $primaryKey = 'refundcourier_id';

    /**
     * @var boolean
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'refundcourier_title',
    ];

    const CREATED_AT = 'refundcourier_created';
    const UPDATED_AT = 'refundcourier_updated';

    /**
     * relatioship business rules:
     *         - the Creator (user) can have many Refunds
     *         - the Refund belongs to one Creator (user)
     */
    public function refunds()
    {
        return $this->hasMany('App\Models\Refund', 'refund_courierid', 'refundcourier_id');
    }
}
