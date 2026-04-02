<?php

namespace App\Http\Controllers\Api;
use App\Traits\ApiResponse;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Sewa Barang API Documentation",
    description: "Dokumentasi API untuk platform dashboard Sewa Barang",
    contact: new OA\Contact(email: "admin@example.com")
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST,
    description: "API Server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer"
)]
class BaseController
{
    use ApiResponse;
}
