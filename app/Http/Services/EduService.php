<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Http\Resources\EducationResource;
use App\Models\Description;
use App\Models\Education;
use Exception;
use Illuminate\Support\Facades\DB;

class EduService
{
    /**
     * EducationModel instance
     *
     * @var Education $service
     */
    private Education $model;

    /**
     * Constructor Method.
     *
     * @param Education $model EducationModel instance.
     *
     * @return void
     */
    public function __construct(Education $model)
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

        return ServiceResult::success(data: new EducationResource($exp));
    }

    /**
     * Tries to create an exp.
     *
     * @param string $image        The exp's image path.
     * @param string $start        The exp's email.
     * @param string $end          The exp's password.
     * @param array  $descriptions The exp's descriptions.
     *
     * @return ServiceResult The result of the operation.
     */
    public function create(
        string $image,
        string $start,
        string $end,
        array $descriptions = []
    ): ServiceResult {
        try {
            DB::beginTransaction();
            $this->model->image = $image;
            $this->model->start = $start;
            $this->model->end   = $end;
            $this->model->save();

            foreach ($descriptions as $description) {
                $descriptionModel       = new Description();
                $descriptionModel->name = $description['name'];
                $descriptionModel->lang = $description['lang'];
                $descriptionModel->description = $description['description'];
                $descriptionModel->type        = 'exp';
                $this->model->descriptions()->save($descriptionModel);
            }
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
     * @param int    $id           The exp's id.
     * @param string $image        The exp's image path.
     * @param string $start        The exp's start date.
     * @param string $end          The exp's end date.
     * @param array  $descriptions The exp's descriptions.
     *
     * @return ServiceResult The result of the operation.
     */
    public function update(
        int $id,
        string $image,
        string $start,
        string $end,
        array $descriptions = []
    ): ServiceResult {
        DB::beginTransaction();
        try {
            $exp = $this->model->findOrFail($id);

            if (!empty($image)) {
                $exp->image = $image;
            }
            if (!empty($start)) {
                $exp->start = $start;
            }
            if (!empty($end)) {
                $exp->end = $end;
            }
            $exp->save();

            if (!empty($descriptions)) {
                foreach ($descriptions as $description) {
                    $descriptionModel = Description::findOrFail(($description['id'] ?? 0));
                    if (isset($description['lang']) && !empty($description['lang'])) {
                        $descriptionModel->lang = $description['lang'];
                    }

                    if (isset($description['name']) && !empty($description['name'])) {
                        $descriptionModel->name = $description['name'];
                    }

                    if (isset($description['description']) && !empty($description['description'])) {
                        $descriptionModel->description = $description['description'];
                    }

                    $descriptionModel->save();
                }
            }

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
