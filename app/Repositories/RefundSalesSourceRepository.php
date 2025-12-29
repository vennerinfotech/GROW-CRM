<?php

namespace App\Repositories;

use App\Models\RefundSalesSource;
use Illuminate\Http\Request;

class RefundSalesSourceRepository
{
    protected $sources;

    public function __construct(RefundSalesSource $sources)
    {
        $this->sources = $sources;
    }

    public function search($id = '')
    {
        $sources = $this->sources->newQuery();

        if (is_numeric($id)) {
            $sources->where('refundsalessource_id', $id);
        }

        return $sources->orderBy('refundsalessource_id', 'desc')->get();
    }

    public function create()
    {
        $source = new $this->sources;
        $source->refundsalessource_title = request('refundsalessource_title');

        if ($source->save()) {
            return $source->refundsalessource_id;
        }
        return false;
    }

    public function update($id)
    {
        if (!$source = $this->sources->find($id)) {
            return false;
        }

        $source->refundsalessource_title = request('refundsalessource_title');

        if ($source->save()) {
            return $source->refundsalessource_id;
        }
        return false;
    }
}
