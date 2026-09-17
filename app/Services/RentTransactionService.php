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
            $data['region_id'] = (int)$data['region_id'];
            $data['qty'] = (int)$data['qty'];
            $data['rent_price'] = (int)$data['rent_price'];
            $tx = $this->repo->create([
                ...$data,
                'product_id' => $product->id,
            ]);
            $tx->load(['product','region']);
            \App\Models\TransactionLog::create([
                'transaction_id' => $tx->id,
                'product_id' => $product->id,
                'user_id' => auth()->id() ?? 1,
                'action' => $tx->status ?? 'rent',
                'from_status' => null,
                'to_status' => $tx->status,
                'metadata' => ['qty'=>$tx->qty,'rent_price'=>$tx->rent_price,'renter'=>$tx->renter_name],
            ]);
            return $tx;
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

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function destroy($id)
    {
        return $this->repo->destroy($id);
    }

    public function update(array $data ,$id)
    {
        $old = $this->repo->find($id);
        if (isset($data['status']) && $data['status']==='returned' && isset($data['return_date']) && isset($data['expected_return_date'])) {
            try {
                if (\Carbon\Carbon::parse($data['return_date'])->gt(\Carbon\Carbon::parse($data['expected_return_date'])->addHour())) {
                    $data['status']='overdue';
                }
            } catch (\Exception $e) {}
        } elseif (isset($data['status']) && $data['status']==='returned' && isset($data['return_date']) && $old && $old->expected_return_date) {
            try {
                if (\Carbon\Carbon::parse($data['return_date'])->gt(\Carbon\Carbon::parse($old->expected_return_date)->addHour())) {
                    $data['status']='overdue';
                }
            } catch (\Exception $e) {}
        }
        if (isset($data['region_id'])) $data['region_id']=(int)$data['region_id'];
        if (isset($data['qty'])) $data['qty']=(int)$data['qty'];
        if (isset($data['rent_price'])) $data['rent_price']=(int)$data['rent_price'];
        $updated = $this->repo->update($data, $id);
        $updated->load(['product','region']);
        if (isset($data['status']) && $old && $old->status !== $data['status']) {
            \App\Models\TransactionLog::create([
                'transaction_id' => $updated->id,
                'product_id' => $updated->product_id,
                'user_id' => auth()->id() ?? 1,
                'action' => $data['status'],
                'from_status' => $old->status,
                'to_status' => $data['status'],
                'metadata' => ['return_date'=>$data['return_date'] ?? null],
            ]);
        }
        return $updated;
    }
}
