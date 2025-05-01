<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    protected function applySearch($query, $search)
    {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search['q'] . '%')
            ->orWhere('email', 'like', '%' . $search['q'] . '%')
            ->orWhere('mobile', 'like', '%' . $search['q'] . '%');
        });
        if (!empty($search['specialization'])) {
            $query->where('specialization', $search['specialization']);
        }
        return $query;
    }

    public function findWithBookings($id)
    {
        return $this->findById($id)->load('bookings');
    }


}
