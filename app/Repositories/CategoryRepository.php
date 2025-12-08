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

    public function all()
    {
        return  $this->model->get();
    }

    public function paginate($limit = 10)
    {
        return $this->model->paginate($limit);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function destroy($id)
    {
        return $this->model->where('id', $id)->delete();
    }
}
