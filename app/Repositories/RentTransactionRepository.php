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
        return $this->model->with(['region', 'product', 'creator.region'])->orderBy('id', 'ASC')->get();
    }

    public function paginate($limit = 10)
    {
        return $this->model->with(['region', 'product', 'creator.region'])->orderBy('id', 'ASC')->paginate($limit);
    }

    public function allFiltered($regionId = null, $createdBy = null)
    {
        return $this->filteredQuery($regionId, $createdBy)->orderBy('id', 'ASC')->get();
    }

    public function paginateFiltered($limit = 10, $regionId = null, $createdBy = null)
    {
        return $this->filteredQuery($regionId, $createdBy)->orderBy('id', 'ASC')->paginate($limit ?? 10);
    }

    protected function filteredQuery($regionId = null, $createdBy = null)
    {
        $q = $this->model->with(['region', 'product', 'creator.region']);
        if ($regionId) $q->where('region_id', (int) $regionId);
        if ($createdBy) $q->where('created_by', (int) $createdBy);
        return $q;
    }

    public function find($id)
    {
        return $this->model->with(['region', 'product', 'creator.region'])->find($id);
    }
}
