<?php


namespace App\Providers;

use App\Http\Controllers\Api\RentTransactionController;
use App\Repositories\CategoryRepository;
use App\Repositories\Interface\CategoryRepositoryInterface;
use App\Repositories\Interface\RegionRepositoryInterface;
use App\Repositories\Interface\RentTransactionRepositoryInterface;
use App\Repositories\RegionRepository;
use App\Repositories\RentTransactionRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\Interface\TransactionLogRepositoryInterface;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Repositories\TransactionLogRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(RegionRepositoryInterface::class, RegionRepository::class);
        $this->app->bind(RentTransactionRepositoryInterface::class, RentTransactionRepository::class);
        $this->app->bind(TransactionLogRepositoryInterface::class, TransactionLogRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
