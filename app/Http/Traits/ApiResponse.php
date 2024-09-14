<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Returns a success message to the user.
     *
     * @param string $message Success message.
     * @param array  $data    Data to be returned
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function success(string $message = '', array $data = []): JsonResponse
    {
        if (empty($message)) {
            $message = __('messages.successfulOperation');
        }

        return response()->json(
            [
                'success' => true,
                'status'  => 200,
                'message' => $message,
                'data'    => $data,
            ],
            200
        );
    }

    /**
     * Returns a failure message to the user.
     *
     * @param string $message Failure message.
     * @param array  $data    Data to be returned
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(string $message = '', array $data = []): JsonResponse
    {
        if (empty($message)) {
            $message = __('messages.failedOperation');
        }

        return response()->json(
            [
                'success' => false,
                'status'  => 500,
                'message' => $message,
                'data'    => $data,
            ],
            500
        );
    }

    /**
     * Returns a bad request message to the user.
     *
     * @param string $message bad request message.
     * @param array  $data    Data to be returned
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function badRequest(string $message = '', array $data = []): JsonResponse
    {
        return response()->json(
            [
                'success' => false,
                'status'  => 400,
                'message' => $message,
                'data'    => $data,
            ],
            400
        );
    }

    /**
     * Returns a unauthorized message to the user.
     *
     * @param string $message unauthorized message.
     * @param array  $data    Data to be returned
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized(string $message = '', array $data = []): JsonResponse
    {
        if (empty($message)) {
            $message = __('messages.unauthorized');
        }

        return response()->json(
            [
                'success' => false,
                'status'  => 401,
                'message' => $message,
                'data'    => $data,
            ],
            401
        );
    }
}
