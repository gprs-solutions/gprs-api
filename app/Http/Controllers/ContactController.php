<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class ContactController
{
    use ApiResponse;

    /**
     * ContactService instance
     *
     * @var ContactService $service
     */
    private ContactService $service;

    /**
     * Constructor method.
     *
     * @param ContactService $service The Contact service.
     *
     * @return void
     */
    public function __construct(ContactService $service)
    {
        $this->service = $service;
    }

    /**
     * Validates if a request to get an contact is valid.
     *
     * @param Request $request Request with the contact info.
     *
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $id        = ($request->route('id') ?? $request->input('id'));
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:contacts,id']
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
     * Validates if a request to create an contact is valid.
     *
     * @param Request $request Request with the contact info.
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'    => 'required|min:2|max:100',
                'email'   => 'required|email|unique:App\Models\Contact,email',
                'message' => 'required|max:1000',
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
            $request->input('message'),
        );

        if (!$result->success) {
            return $this->badRequest($result->message);
        }

        return $this->success();
    }
}
