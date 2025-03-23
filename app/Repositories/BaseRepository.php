<?php

namespace App\Repositories;

use App\Repositories\Interfaces\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements EloquentRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function getAllPaginated($search, $sortField, $sortDirection, $perPage)
    {
        return $this->model
            ->when($search, function ($query) use ($search) {
                $this->applySearch($query, $search);
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->findById($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        return $this->findById($id)->delete();
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findByColumn($column, $value)
    {
        return $this->model->where($column, $value)->first();
    }

    abstract protected function applySearch($query, $search);
}