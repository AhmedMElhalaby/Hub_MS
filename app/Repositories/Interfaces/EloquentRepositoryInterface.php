<?php

namespace App\Repositories\Interfaces;

interface EloquentRepositoryInterface
{
    public function all();
    public function getAllPaginated($search, $sortField, $sortDirection, $perPage);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findById($id);
    public function findByColumn($column, $value);
}