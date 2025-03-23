<?php

namespace App\Repositories;

use App\Models\Plan;
use App\Models\Setting;

class PlanRepository extends BaseRepository
{
    public function __construct(Plan $model)
    {
        parent::__construct($model);
    }

    protected function applySearch($query, $search)
    {
        return $query->where('type', 'like', '%' . $search . '%')
            ->orWhere('price', 'like', '%' . $search . '%');
    }

    public function getAllPaginatedWithMikrotik($search, $sortField, $sortDirection, $perPage)
    {
        $mikrotikEnabled = Setting::get('mikrotik_enabled', false);

        return $this->model
            ->when($search, function ($query) use ($search) {
                $this->applySearch($query, $search);
            })
            ->when(!$mikrotikEnabled, function ($query) {
                $query->where('mikrotik_profile', null);
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);
    }

    public function findWithBookings($id)
    {
        return $this->findById($id)->load('bookings');
    }
}