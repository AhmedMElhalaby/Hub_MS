<?php

namespace App\Repositories;

use App\Models\MikrotikProfile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MikrotikProfileRepository extends BaseRepository
{
    public function __construct(MikrotikProfile $model)
    {
        parent::__construct($model);
    }
    protected function applySearch($query, $search)
    {
        $query->Where('name', 'like', '%' . $search['q'] . '%');
        return $query;
    }
}
