<?php


namespace App\Services;

use App\Repositories\Interface\RentTransactionRepositoryInterface;
use Illuminate\Validation\ValidationException;
use App\Models\Product;
use Illuminate\Support\Facades\DB;


class RentTransactionService
{
    public function __construct(
        protected RentTransactionRepositoryInterface $repo,
    )
    {
    }

    public function createByQr(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = Product::where('qr_uuid', $data['qr_uuid'])->first();
        
            if(!$product){
                throw ValidationException::withMessages([
                    'qr_uuid' => 'Product not found',
                ]);
            }
            return $this->repo->create([
                ...$data,   
                'product_id' => $product->id,
            ]);
        });
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
