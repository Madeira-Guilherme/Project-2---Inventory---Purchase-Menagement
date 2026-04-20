<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Inventory & Purchase Management API",
    version: "1.0.0",
    description: "Project 2"
)]

#[OA\Server(
    url: "http://localhost:8000",
    description: "Local Server"
)]

#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]

#[OA\OpenApi(
    security: [["bearerAuth" => []]]
)]

class OpenApi {}
