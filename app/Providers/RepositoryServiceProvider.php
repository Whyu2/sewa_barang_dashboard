<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\ProductRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }
}
