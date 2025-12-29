<?php

namespace App\Repositories;

use App\Models\RefundErrorSource;
use Illuminate\Http\Request;

class RefundErrorSourceRepository
{
    protected $sources;

    public function __construct(RefundErrorSource $sources)
    {
        $this->sources = $sources;
    }

    public function search($id = '')
    {
        $sources = $this->sources->newQuery();

        if (is_numeric($id)) {
            $sources->where('refunderrorsource_id', $id);
        }

        return $sources->orderBy('refunderrorsource_id', 'desc')->get();
    }

    public function create()
    {
        $source = new $this->sources;
        $source->refunderrorsource_title = request('refunderrorsource_title');

        if ($source->save()) {
            return $source->refunderrorsource_id;
        }
        return false;
    }

    public function update($id)
    {
        if (!$source = $this->sources->find($id)) {
            return false;
        }

        $source->refunderrorsource_title = request('refunderrorsource_title');

        if ($source->save()) {
            return $source->refunderrorsource_id;
        }
        return false;
    }
}
