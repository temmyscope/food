<?php

namespace App\Traits;

use App\Enums\HttpStatus;
use Illuminate\Http\JsonResponse;

/**
 *
 */
trait RequestResponseTrait
{

    /**
     * Return JSON Response
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public function jsonResponse(array $data, int $code = 200): JsonResponse
    {
        return response()->json([ 
            ...$data, 'code' => $code,
            'status' => HttpStatus::from($code)->status()
        ], $code);
    }

    /**
     * Some operation has completed successfully
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function respondWithSuccess($data = [], string $message = 'Success', int $code = 200)
    {
        return $this->jsonResponse([
            'data' => $data, 'message' => ( 
                $message == 'Success' 
            ) ? strtolower(HttpStatus::from($code)->message()) : $message
        ], $code);
    }

    /**
     * Respond with an Error
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function respondWithError($data = [], string $message = 'There was an error',  int $code = 400)
    {
        return $this->jsonResponse([
            'data' => $data, 'message' => ( 
                $message == 'There was an error' 
            ) ? strtolower(HttpStatus::from($code)->message()) : $message
        ], $code);
    }
}