<?php

namespace App\Http\Controllers;

use App\Http\Services\AuthService;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class AuthController
{
    use ApiResponse;

    /**
     * AuthService instance
     *
     * @var AuthService $service
     */
    private AuthService $service;

    /**
     * Constructor method.
     *
     * @param AuthService $service The User service.
     *
     * @return void
     */
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Tries to log a user in.
     *
     * @param Request $request Request with the user info.
     *
     * @return HttpResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email'    => 'required|email|exists:users,email',
                'password' => 'required|min:8|max:255',
            ]
        );

        if ($validator->fails()) {
            Log::info(
                gethostname()
                    . ' [' . get_class() . '::' . __FUNCTION__ . '] '
                    . 'Invalid parameters at ' . Arr::get(debug_backtrace(), '1.function', '') . ': '
                    . $validator->messages()->toJson()
            );
            return $this->badRequest($validator->messages());
        }

        $result = $this->service->login(
            $request->input('email'),
            $request->input('password')
        );

        if (!$result->success) {
            return $this->unauthorized($result->message);
        }

        return $this->success(data: [$result->data]);
    }
}
