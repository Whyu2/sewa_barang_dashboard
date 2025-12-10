<?php


namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\Interface\CategoryRepositoryInterface;
use App\Repositories\Interface\RegionRepositoryInterface;
use App\Repositories\RegionRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\ProductRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(RegionRepositoryInterface::class, RegionRepository::class);
    }
}
