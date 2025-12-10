<?php
namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interface\CategoryRepositoryInterface;
class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        $this->model = $model;
    }

}
