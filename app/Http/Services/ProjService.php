<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Http\Resources\ProjectResource;
use App\Models\Description;
use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\DB;

class ProjService
{
    /**
     * ProjectModel instance
     *
     * @var Project $service
     */
    private Project $model;

    /**
     * Constructor Method.
     *
     * @param Project $model ProjectModel instance.
     *
     * @return void
     */
    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    /**
     * Tries to list all saved projs.
     *
     * @return ServiceResult The result of the operation.
     */
    public function list(): ServiceResult
    {
        try {
            $proj = $this->model->get();
        } catch (Exception $e) {
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success(data: ProjectResource::collection($proj));
    }

    /**
     * Tries to get data from a specific proj.
     *
     * @param string $id The proj's id.
     *
     * @return ServiceResult The result of the operation.
     */
    public function get(
        int $id,
    ): ServiceResult {
        try {
            $proj = $this->model->findOrFail($id);
        } catch (Exception $e) {
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success(data: new ProjectResource($proj));
    }

    /**
     * Tries to create an proj.
     *
     * @param string $link         The proj's link path.
     * @param string $image        The proj's image path.
     * @param string $start        The proj's email.
     * @param string $end          The proj's password.
     * @param array  $descriptions The proj's descriptions.
     *
     * @return ServiceResult The result of the operation.
     */
    public function create(
        string $link,
        string $image,
        string $start,
        string $end,
        array $descriptions = []
    ): ServiceResult {
        try {
            DB::beginTransaction();
            $this->model->link  = $link;
            $this->model->image = $image;
            $this->model->start = $start;
            $this->model->end   = $end;
            $this->model->save();

            foreach ($descriptions as $description) {
                $descriptionModel       = new Description();
                $descriptionModel->name = $description['name'];
                $descriptionModel->lang = $description['lang'];
                $descriptionModel->description = $description['description'];
                $descriptionModel->type        = 'proj';
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
     * Tries to update an proj.
     *
     * @param int    $id           The proj's id.
     * @param string $link         The proj's link path.
     * @param string $image        The proj's image path.
     * @param string $start        The proj's start date.
     * @param string $end          The proj's end date.
     * @param array  $descriptions The proj's descriptions.
     *
     * @return ServiceResult The result of the operation.
     */
    public function update(
        int $id,
        string $link,
        string $image,
        string $start,
        string $end,
        array $descriptions = []
    ): ServiceResult {
        DB::beginTransaction();
        try {
            $proj = $this->model->findOrFail($id);

            if (!empty($link)) {
                $proj->link = $link;
            }
            if (!empty($image)) {
                $proj->image = $image;
            }
            if (!empty($start)) {
                $proj->start = $start;
            }
            if (!empty($end)) {
                $proj->end = $end;
            }
            $proj->save();

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
     * Tries to suspend a specific proj.
     *
     * @param string $id The proj's id.
     *
     * @return ServiceResult The result of the operation.
     */
    public function suspend(
        int $id,
    ): ServiceResult {
        DB::beginTransaction();
        try {
            $proj = $this->model->findOrFail($id);
            $proj->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success();
    }
}
