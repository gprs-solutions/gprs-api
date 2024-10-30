<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Http\Resources\ContactResource;
use App\Mail\ClientMail;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Throw_;

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

            // Sending contact emails.
            if (!$this->email()) {
                throw new Exception('messages.failedOperation');
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
     * Sends the contact emails.
     *
     * @return bool weather it worked or not.
     */
    private function email()
    {
        try {
            $data = [
                'name'    => $this->model->name,
                'email'   => $this->model->email,
                'message' => $this->model->message,
            ];
            // Send email to notify website admin.
            Mail::to(config('mail.admin.address'))->send(new ContactMail($data));
            // Send success email to client.
            Mail::to($data['email'])->send(new ClientMail($data));

            return true;
        } catch (Exception $e) {
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return false;
        }
    }
}
