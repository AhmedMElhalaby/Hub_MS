<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Enums\FinanceType;

class ExpenseRepository extends BaseRepository
{
    public function __construct(Expense $model)
    {
        parent::__construct($model);
    }

    protected function applySearch($query, $search)
    {
        $query->Where('amount', 'like', '%' . $search['q'] . '%');
        if (!empty($search['category'])) {
            $query->where('category', $search['category']);
        }
        return $query;
    }

    public function create(array $data)
    {
        $expense = parent::create($data);
        $expense->finances()->create([
            'amount' => $expense->amount,
            'type' => FinanceType::Expense,
            'note' => $expense->note ?? null
        ]);
        return $expense;
    }

    public function update($id, array $data)
    {
        $expense = parent::update($id, $data);
        $expense->finances()->updateOrCreate(
            ['expense_id' => $expense->id],
            [
                'amount' => $data['amount'],
                'type' => FinanceType::Expense,
                'note' => $data['note'] ?? null
            ]
        );
        return $expense;
    }

    public function delete($id)
    {
        $expense = $this->findById($id);
        $expense->finances()->delete();
        return parent::delete($id);
    }

    public function findWithFinances($id)
    {
        return $this->findById($id)->load('finances');
    }
}
