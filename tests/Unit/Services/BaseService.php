<?php

namespace Tests\Unit\Services;

use App\Http\Services\ServiceResult;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BaseService extends TestCase
{
    use DatabaseTransactions;

    /**
     * The user instance.
     *
     * @var User $userInstance
     */
    protected User $userInstance;

    /**
     * Default success return.
     *
     * @var ServiceResult $defaultSuccessReturn
     */
    protected ServiceResult $defaultSuccessReturn;

    /**
     * Default failure return.
     *
     * @var ServiceResult $defaultFailureReturn
     */
    protected ServiceResult $defaultFailureReturn;

    /**
     * Setup method.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->createSuperUser();
        $this->populateServiceResponses();
    }

    /**
     * Creates a super user for testing purposes.
     *
     * @return void
     */
    private function createSuperUser()
    {
        $user = User::factory()->create(
            ['email' => 'gprs@email.com']
        );
        $role = Role::where('code', 'GPRS_SUPER_USR')->first();
        $user->roles()->attach($role->id);

        $this->userInstance = $user;
    }

    /**
     * Defines the default success and service Responses.
     *
     * @return void
     */
    private function populateServiceResponses(): void
    {
        $this->defaultSuccessReturn = ServiceResult::success(__('messages.successfulOperation'), ['success' => true]);
        $this->defaultFailureReturn = ServiceResult::failure(__('messages.failedOperation'), ['success' => false]);
    }
}
