<?php
namespace App\Repositories;

use App\Models\Region;
use App\Repositories\Interface\RegionRepositoryInterface;

class RegionRepository extends BaseRepository implements RegionRepositoryInterface
{
    public function __construct(Region $model)
    {
        $this->model = $model;
    }

    public function update($data, $id)
    {
        return $this->model->where('id', $id)->update([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
    }
}
