<?php


namespace App\Services;

use App\Repositories\Interface\ProductRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $repo,
        protected QRCodeService $qrCodeService
    )
    {
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $regions = json_decode($data['product_region'] ?? [], true);
            unset($data['product_region']);

            $data['qr_uuid'] = uuid_create();

            $qrImage = $this->qrCodeService->generateQRCode($data['qr_uuid']);
            $filePath = 'qrcodes/' . $data['qr_uuid'] . '.png';

            Storage::disk('public')->put($filePath, $qrImage);
            $data['qr_code_url'] = asset('storage/' . $filePath);


            // file upload
            $productImage = $data['product_image'];
            $filePath = 'product_images/' . $productImage->hashName();

            Storage::disk('public')->put($filePath, file_get_contents($productImage));
            $data['product_image'] = asset('storage/' . $filePath);
        
            $data['photo_url'] = asset('storage/' . $filePath);
            $product = $this->repo->create($data);

            foreach ($regions as $row) {
                $product->productRegion()->create([
                    'region_id' => $row['region_id'],
                    'qty'       => $row['qty'],
                ]);
            }
          return $product->load('productRegion');
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

    public function update(array $data, $id)
{
    return DB::transaction(function () use ($data, $id) {

        $regions = json_decode($data['product_region'] ?? [], true);
        unset($data['product_region']);

        $product = $this->repo->update($data, $id);
        if (!empty($data['product_image'])) {
            $file = $data['product_image'];
            $filePath = 'product_images/' . $file->hashName();
            Storage::disk('public')->put($filePath, file_get_contents($file));
            $product->photo_url = asset('storage/' . $filePath);
            $product->save();
        }
        
        // reset relasi product region
        $product->productRegion()->delete();

        foreach ($regions as $row) {
            $product->productRegion()->create($row);
        }

        return $product->load('productRegion');
    });
}

    public function findByQrCode($qr_uuid)
    {
        $product = Product::where('qr_uuid', $qr_uuid)->first();
        if(!$product){
                throw ValidationException::withMessages([
                    'qr_uuid' => 'Product not found',
                ]);
        }
        return $this->repo->find($product->id);
    }


}
