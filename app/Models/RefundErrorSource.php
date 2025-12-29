<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundErrorSource extends Model
{
    protected $table = 'refund_error_sources';
    protected $primaryKey = 'refunderrorsource_id';
    protected $guarded = ['refunderrorsource_id'];

    const CREATED_AT = 'refunderrorsource_created';
    const UPDATED_AT = 'refunderrorsource_updated';

    public function refunds()
    {
        return $this->hasMany('App\Models\Refund', 'refund_error_sourceid', 'refunderrorsource_id');
    }
}
