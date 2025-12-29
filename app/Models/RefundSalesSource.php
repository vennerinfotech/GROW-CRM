<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundSalesSource extends Model
{
    protected $table = 'refund_sales_sources';
    protected $primaryKey = 'refundsalessource_id';
    protected $guarded = ['refundsalessource_id'];

    const CREATED_AT = 'refundsalessource_created';
    const UPDATED_AT = 'refundsalessource_updated';

    public function refunds()
    {
        return $this->hasMany('App\Models\Refund', 'refund_sales_sourceid', 'refundsalessource_id');
    }
}
