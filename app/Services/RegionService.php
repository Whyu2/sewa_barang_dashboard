<?php


namespace App\Services;

use App\Repositories\Interface\RegionRepositoryInterface;

class RegionService
{
    public function __construct(
        protected RegionRepositoryInterface $repo
    )
    {
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function all()
    {
        return $this->repo->all();
    }

    public function paginate($limit)
    {
        return $this->repo->paginate( $limit);
    }

    public function destroy($id)
    {
        return $this->repo->destroy($id);
    }

    public function update(array $data ,$id)
    {
        return $this->repo->update($data, $id);
    }
}
