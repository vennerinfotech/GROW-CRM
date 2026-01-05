<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadOccasion extends Model
{
    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */
    protected $table = 'leads_occasions';

    protected $primaryKey = 'leadoccasions_id';
    protected $guarded = ['leadoccasions_id'];
    protected $dateFormat = 'Y-m-d H:i:s';

    const CREATED_AT = 'leadoccasions_created';
    const UPDATED_AT = 'leadoccasions_updated';
}
