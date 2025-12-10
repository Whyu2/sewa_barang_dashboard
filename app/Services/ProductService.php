<?php


namespace App\Services;

use App\Repositories\Interface\ProductRepositoryInterface;
use Illuminate\Support\Facades\Storage;
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

        $data['qr_uuid'] = uuid_create();

        // generate QR
        $qrImage = $this->qrCodeService->generateQRCode($data['qr_uuid']);

        // file path relative to /storage/app/public
        $filePath = 'qrcodes/' . $data['qr_uuid'] . '.png';

        // save into public disk
        Storage::disk('public')->put($filePath, $qrImage);

        // store public URL
        $data['qr_code_url'] = asset('storage/' . $filePath);

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
