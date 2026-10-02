<?php

namespace App;

/**
 * @OA\Info(
 *     title="Laravel Passport API",
 *     version="1.0.0",
 *     description="API documentation for Laravel Passport authentication",
 *     @OA\Contact(
 *         email="api@example.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class Swagger
{
}
