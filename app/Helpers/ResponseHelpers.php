<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelpers {
    public static function ok($data = [], string $message = 'Request Success!')
    {
        return self::response($data, $message);
    }

    public function badRequest($data = [], string $message = 'Bad Request!')
    {
        return self::response($data, $message, JsonResponse::HTTP_BAD_REQUEST);
    }

    public function internalError($data = [], string $message = 'Internal Error!')
    {
        return self::response($data, $message, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function unprocessEntity($data = [], string $message = 'Unprocess Entity!')
    {
        return self::response($data, $message, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    private static function response($data, string $message = 'Request Success!', int $status = 200, $headers = [])
    {
        return response()->json([
                'message' => $message,
                'data' => $data,
            ],
            $status,
            $headers
        );
    }
}
