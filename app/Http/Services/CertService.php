<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Http\Resources\CertificationResource;
use App\Models\Description;
use App\Models\Certification;
use Exception;
use Illuminate\Support\Facades\DB;

class CertService
{
    /**
     * CertificationModel instance
     *
     * @var Certification $service
     */
    private Certification $model;

    /**
     * Constructor Method.
     *
     * @param Certification $model CertificationModel instance.
     *
     * @return void
     */
    public function __construct(Certification $model)
    {
        $this->model = $model;
    }

    /**
     * Tries to get data from a specific cert.
     *
     * @param string $id The cert's id.
     *
     * @return ServiceResult The result of the operation.
     */
    public function get(
        int $id,
    ): ServiceResult {
        try {
            $cert = $this->model->findOrFail($id);
        } catch (Exception $e) {
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success(data: new CertificationResource($cert));
    }

    /**
     * Tries to create an cert.
     *
     * @param string $link         The cert's link path.
     * @param string $image        The cert's image path.
     * @param string $start        The cert's email.
     * @param string $end          The cert's password.
     * @param array  $descriptions The cert's descriptions.
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
                $descriptionModel->type        = 'cert';
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
     * Tries to update an cert.
     *
     * @param int    $id           The cert's id.
     * @param string $link         The cert's link path.
     * @param string $image        The cert's image path.
     * @param string $start        The cert's start date.
     * @param string $end          The cert's end date.
     * @param array  $descriptions The cert's descriptions.
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
            $cert = $this->model->findOrFail($id);

            if (!empty($link)) {
                $cert->link = $link;
            }
            if (!empty($image)) {
                $cert->image = $image;
            }
            if (!empty($start)) {
                $cert->start = $start;
            }
            if (!empty($end)) {
                $cert->end = $end;
            }
            $cert->save();

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
     * Tries to suspend a specific cert.
     *
     * @param string $id The cert's id.
     *
     * @return ServiceResult The result of the operation.
     */
    public function suspend(
        int $id,
    ): ServiceResult {
        DB::beginTransaction();
        try {
            $cert = $this->model->findOrFail($id);
            $cert->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success();
    }
}
