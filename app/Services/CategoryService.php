<?php


namespace App\Services;

use App\Repositories\Interface\CategoryRepositoryInterface;

class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $repo
    )
    {
    }

    public function createCategory(array $data)
    {
        if (empty($data['name'])) {
            throw new \Exception("Category name is required");
        }

        return $this->repo->create($data);
    }

    public function getAllCategories()
    {
        return $this->repo->all();
    }

    public function getPaginatedCategories($limit)
    {
        return $this->repo->paginate($limit);

    }

    public function destroyCategory($id)
    {
        return $this->repo->destroy($id);
    }

    public function updateCategory(array $data ,$id)
    {
        return $this->repo->update($data, $id);
    }

}
