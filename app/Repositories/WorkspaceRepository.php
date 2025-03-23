<?php

namespace App\Repositories;

use App\Models\Workspace;

class WorkspaceRepository extends BaseRepository
{
    public function __construct(Workspace $model)
    {
        parent::__construct($model);
    }

    protected function applySearch($query, $search)
    {
        return $query->where('desk', 'like', '%' . $search . '%');
    }

    public function findWithBookings($id)
    {
        return $this->findById($id)->load('bookings');
    }

    public function markAsAvailable($id)
    {
        $workspace = $this->findById($id);
        $workspace->markAsAvailable();
        return $workspace;
    }

    public function markAsBooked($id)
    {
        $workspace = $this->findById($id);
        $workspace->markAsBooked();
        return $workspace;
    }

    public function getAvailable()
    {
        return $this->model->available()->get();
    }
}