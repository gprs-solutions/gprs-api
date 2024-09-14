<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class UserController
{
    use ApiResponse;

    /**
     * UserService instance
     *
     * @var UserService $service
     */
    private UserService $service;

    /**
     * Constructor method.
     *
     * @param UserService $service The User service.
     *
     * @return void
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Validates if a request to get an user is valid.
     *
     * @param Request $request Request with the user info.
     *
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $id        = ($request->route('id') ?? $request->input('id'));
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:users,id']
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

        $result = $this->service->get(
            $id,
        );

        if (!$result->success) {
            return $this->badRequest($result->message);
        }

        return $this->success(data: [$result->data]);
    }

    /**
     * Validates if a request to create an user is valid.
     *
     * @param Request $request Request with the user info.
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'            => 'required|min:2|max:100',
                'email'           => 'required|email|unique:App\Models\User,email',
                'password'        => 'required|min:8|max:255',
                'passwordConfirm' => 'required|same:password',
                'roles'           => 'required|array',
                'roles.*'         => 'nullable|exists:roles,code',
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

        $result = $this->service->create(
            $request->input('name'),
            $request->input('email'),
            $request->input('password'),
            $request->input('roles', [])
        );

        if (!$result->success) {
            return $this->badRequest($result->message);
        }

        return $this->success();
    }

    /**
     * Validates if a request to update an user is valid.
     *
     * @param Request $request Request with the user info.
     *
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $id        = ($request->route('id') ?? $request->input('id'));
        $validator = Validator::make(
            array_merge(['id' => $id], $request->all()),
            [
                'id'              => 'required|integer|exists:users,id',
                'name'            => 'sometimes|min:2|max:100',
                'email'           => 'sometimes|email|unique:App\Models\User,email',
                'password'        => 'nullable|min:8|max:255|required_with:passwordConfirm',
                'passwordConfirm' => 'nullable|min:8|max:255|required_with:password|same:password',
                'roles'           => 'nullable|array',
                'roles.*'         => 'nullable|exists:roles,code',
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

        $result = $this->service->update(
            $id,
            $request->input('name', ''),
            $request->input('email', ''),
            $request->input('password', ''),
            $request->input('roles', [])
        );

        if (!$result->success) {
            return $this->badRequest($result->message);
        }

        return $this->success();
    }

    /**
     * Validates if a request to suspend an user is valid.
     *
     * @param Request $request Request with the user info.
     *
     * @return JsonResponse
     */
    public function suspend(Request $request): JsonResponse
    {
        $id        = ($request->route('id') ?? $request->input('id'));
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:users,id']
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

        $result = $this->service->suspend(
            $id,
        );

        if (!$result->success) {
            return $this->badRequest($result->message);
        }

        return $this->success();
    }
}
