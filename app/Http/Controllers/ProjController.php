<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\ProjService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class ProjController
{
    use ApiResponse;

    /**
     * ProjService instance
     *
     * @var ProjService $service
     */
    private ProjService $service;

    /**
     * Constructor method.
     *
     * @param ProjService $service The project service.
     *
     * @return void
     */
    public function __construct(ProjService $service)
    {
        $this->service = $service;
    }

    /**
     * Validates if a request to list all projects is valid.
     *
     * @param Request $request Request with the project info.
     *
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $result = $this->service->list();

        if (!$result->success) {
            return $this->badRequest($result->message);
        }

        return $this->success(data: [...$result->data]);
    }

    /**
     * Validates if a request to get an project is valid.
     *
     * @param Request $request Request with the project info.
     *
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $id        = ($request->route('id') ?? $request->input('id'));
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:projects,id']
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
     * Validates if a request to create an project is valid.
     *
     * @param Request $request Request with the project info.
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'link'                       => 'required|url|max:255',
                'image'                      => 'required|url|max:255',
                'start'                      => 'required|date_format:Y-m-d',
                'end'                        => 'required|date_format:Y-m-d|after:start',
                'descriptions'               => 'required|array',
                'descriptions.*.lang'        => 'required|size:2',
                'descriptions.*.name'        => 'required|max:75',
                'descriptions.*.description' => 'required|max:1000',
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
            $request->input('link'),
            $request->input('image'),
            $request->input('start'),
            $request->input('end'),
            $request->input('descriptions', [])
        );

        if (!$result->success) {
            return $this->badRequest($result->message);
        }

        return $this->success();
    }

    /**
     * Validates if a request to update an project is valid.
     *
     * @param Request $request Request with the project info.
     *
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $id        = ($request->route('id') ?? $request->input('id'));
        $validator = Validator::make(
            array_merge(['id' => $id], $request->all()),
            [
                'id'                         => 'required|integer|exists:projects,id',
                'link'                       => 'required|url|max:255',
                'image'                      => 'required|url|max:255',
                'start'                      => 'sometimes|date_format:Y-m-d',
                'end'                        => 'sometimes|date_format:Y-m-d|after:start',
                'descriptions'               => 'nullable|array',
                'descriptions.*.id'          => 'nullable|exists:descriptions,id',
                'descriptions.*.lang'        => 'nullable|size:2',
                'descriptions.*.name'        => 'nullable|max:75',
                'descriptions.*.description' => 'nullable|max:1000',
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
            $request->input('link'),
            $request->input('image'),
            $request->input('start'),
            $request->input('end'),
            $request->input('descriptions', [])
        );

        if (!$result->success) {
            return $this->badRequest($result->message);
        }

        return $this->success();
    }

    /**
     * Validates if a request to suspend an project is valid.
     *
     * @param Request $request Request with the project info.
     *
     * @return JsonResponse
     */
    public function suspend(Request $request): JsonResponse
    {
        $id        = ($request->route('id') ?? $request->input('id'));
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:projects,id']
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
