<?php
namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeService
{
public function generateQRCode(string $text): string
{
return QrCode::format('png')->size(300)->generate($text);
}
}
