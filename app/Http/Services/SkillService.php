<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use Exception;
use Illuminate\Support\Facades\DB;

class SkillService
{
    /**
     * SkillModel instance
     *
     * @var Skill $service
     */
    private Skill $model;

    /**
     * Constructor Method.
     *
     * @param Skill $model SkillModel instance.
     *
     * @return void
     */
    public function __construct(Skill $model)
    {
        $this->model = $model;
    }

    /**
     * Tries to get data from a specific exp.
     *
     * @param string $id The exp's id.
     *
     * @return ServiceResult The result of the operation.
     */
    public function get(
        int $id,
    ): ServiceResult {
        try {
            $exp = $this->model->findOrFail($id);
        } catch (Exception $e) {
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success(data: new SkillResource($exp));
    }

    /**
     * Tries to create an exp.
     *
     * @param string $image The exp's image path.
     *
     * @return ServiceResult The result of the operation.
     */
    public function create(
        string $image,
    ): ServiceResult {
        try {
            DB::beginTransaction();
            $this->model->image = $image;
            $this->model->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success();
    }

    /**
     * Tries to update an exp.
     *
     * @param int    $id    The exp's id.
     * @param string $image The exp's image path.
     *
     * @return ServiceResult The result of the operation.
     */
    public function update(
        int $id,
        string $image,
    ): ServiceResult {
        DB::beginTransaction();
        try {
            $exp = $this->model->findOrFail($id);

            if (!empty($image)) {
                $exp->image = $image;
            }
            $exp->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success();
    }

    /**
     * Tries to suspend a specific exp.
     *
     * @param string $id The exp's id.
     *
     * @return ServiceResult The result of the operation.
     */
    public function suspend(
        int $id,
    ): ServiceResult {
        DB::beginTransaction();
        try {
            $exp = $this->model->findOrFail($id);
            $exp->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success();
    }
}
