<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Http\Resources\ContactResource;
use App\Models\Role;
use App\Models\Contact;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ContactService
{
    /**
     * ContactModel instance
     *
     * @var Contact $service
     */
    private Contact $model;

    /**
     * Constructor Method.
     *
     * @param Contact $model ContactModel instance.
     *
     * @return void
     */
    public function __construct(Contact $model)
    {
        $this->model = $model;
    }

    /**
     * Tries to get data from a specific contact.
     *
     * @param string $id The contact's id.
     *
     * @return ServiceResult The result of the operation.
     */
    public function get(
        int $id,
    ): ServiceResult {
        try {
            $contact = $this->model->findOrFail($id);
        } catch (Exception $e) {
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success(data: new ContactResource($contact));
    }

    /**
     * Tries to create an contact.
     *
     * @param string $name    The contact's name.
     * @param string $email   The contact's email.
     * @param string $message The contact's message.
     *
     * @return ServiceResult The result of the operation.
     */
    public function create(
        string $name,
        string $email,
        string $message,
    ): ServiceResult {
        try {
            DB::beginTransaction();
            $this->model->name    = $name;
            $this->model->email   = $email;
            $this->model->message = $message;
            $this->model->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success();
    }
}
