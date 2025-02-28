<?php

namespace App\Traits;

trait WithSorting
{
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $search = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field
            ? $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';

        $this->sortField = $field;
    }

    public function updatedPerPage($value)
    {
        $this->resetPage();
    }
}
