<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "My Laravel API",
    version: "1.0.0",
    description: "API documentation"
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Local Server"
)]
#[OA\OpenApi]
class OpenApi {}
