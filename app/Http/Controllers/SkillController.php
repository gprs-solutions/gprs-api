<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\SkillService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class SkillController
{
    use ApiResponse;

    /**
     * SkillService instance
     *
     * @var SkillService $service
     */
    private SkillService $service;

    /**
     * Constructor method.
     *
     * @param SkillService $service The experience service.
     *
     * @return void
     */
    public function __construct(SkillService $service)
    {
        $this->service = $service;
    }

    /**
     * Validates if a request to get an experience is valid.
     *
     * @param Request $request Request with the experience info.
     *
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $id        = ($request->route('id') ?? $request->input('id'));
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:skills,id']
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
     * Validates if a request to create an experience is valid.
     *
     * @param Request $request Request with the experience info.
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            ['image' => 'required|url|max:255']
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
            $request->input('image'),
        );

        if (!$result->success) {
            return $this->badRequest($result->message);
        }

        return $this->success();
    }

    /**
     * Validates if a request to update an experience is valid.
     *
     * @param Request $request Request with the experience info.
     *
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $id        = ($request->route('id') ?? $request->input('id'));
        $validator = Validator::make(
            array_merge(['id' => $id], $request->all()),
            [
                'id'    => 'required|integer|exists:skills,id',
                'image' => 'required|url|max:255',
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
            $request->input('image'),
        );

        if (!$result->success) {
            return $this->badRequest($result->message);
        }

        return $this->success();
    }

    /**
     * Validates if a request to suspend an experience is valid.
     *
     * @param Request $request Request with the experience info.
     *
     * @return JsonResponse
     */
    public function suspend(Request $request): JsonResponse
    {
        $id        = ($request->route('id') ?? $request->input('id'));
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:skills,id']
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
