<?php
namespace App\Repositories;

use App\Models\RentTransaction;
use App\Repositories\Interface\RentTransactionRepositoryInterface;

class RentTransactionRepository extends BaseRepository implements RentTransactionRepositoryInterface
{
    public function __construct(RentTransaction $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['region', 'product'])->orderBy('id', 'ASC')->get();
    }

    public function paginate($limit = 10)
    {
        return $this->model->with(['region', 'product'])->orderBy('id', 'ASC')->paginate($limit);
    }

    public function find($id)
    {
        return $this->model->with(['region', 'product'])->find($id);
    }
}
