<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * UserModel instance
     *
     * @var User $service
     */
    private User $model;

    /**
     * Constructor Method.
     *
     * @param User $model UserModel instance.
     *
     * @return void
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Tries to get data from a specific user.
     *
     * @param string $id The user's id.
     *
     * @return ServiceResult The result of the operation.
     */
    public function get(
        int $id,
    ): ServiceResult {
        try {
            $user = $this->model->findOrFail($id);
        } catch (Exception $e) {
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success(data: new UserResource($user));
    }

    /**
     * Tries to create an user.
     *
     * @param string $name     The user's name.
     * @param string $email    The user's email.
     * @param string $password The user's password.
     * @param array  $roles    The user's roles.
     *
     * @return ServiceResult The result of the operation.
     */
    public function create(
        string $name,
        string $email,
        string $password,
        array $roles = []
    ): ServiceResult {
        try {
            DB::beginTransaction();
            $this->model->name        = $name;
            $this->model->email       = $email;
            $this->model->password    = Hash::make($password);
            $this->model->profile_img = 'placeholder';
            $this->model->save();

            $rolesIds = Role::whereIn('code', $roles)->pluck('id')->toArray();
            $this->model->roles()->attach($rolesIds);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success();
    }

    /**
     * Tries to update an user.
     *
     * @param int    $id       The user's id.
     * @param string $name     The user's name.
     * @param string $email    The user's email.
     * @param string $password The user's password.
     * @param array  $roles    The user's roles.
     *
     * @return ServiceResult The result of the operation.
     */
    public function update(
        int $id,
        string $name = '',
        string $email = '',
        string $password = '',
        array $roles = []
    ): ServiceResult {
        DB::beginTransaction();
        try {
            $user = $this->model->findOrFail($id);

            if (!empty($name)) {
                $user->name = $name;
            }
            if (!empty($email)) {
                $user->email = $email;
            }
            if (!empty($password)) {
                $user->password = Hash::make($password);
            }

            if (!empty($roles)) {
                $rolesIds = Role::whereIn('code', $roles)->pluck('id')->toArray();
                $user->roles()->sync($rolesIds);
            }

            $user->profile_img = 'placeholder';
            $user->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success();
    }

    /**
     * Tries to suspend a specific user.
     *
     * @param string $id The user's id.
     *
     * @return ServiceResult The result of the operation.
     */
    public function suspend(
        int $id,
    ): ServiceResult {
        DB::beginTransaction();
        try {
            $user = $this->model->findOrFail($id);
            $user->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success();
    }
}
