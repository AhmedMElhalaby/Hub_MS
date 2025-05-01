<?php

namespace App\Repositories;

use App\Enums\WorkspaceStatus;
use App\Models\Workspace;

class WorkspaceRepository extends BaseRepository
{
    public function __construct(Workspace $model)
    {
        parent::__construct($model);
    }

    protected function applySearch($query, $search)
    {
        $query->where('desk', 'like', '%' . $search['q'] . '%');
        if (!empty($search['status'])) {
            $query->where('status', $search['status']);
        }
        return $query;
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
        return $this->model
            ->where('status', WorkspaceStatus::Available)
            ->get();
    }
}
