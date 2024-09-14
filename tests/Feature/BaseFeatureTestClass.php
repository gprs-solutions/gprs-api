<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Http\Services\AuthService;

class BaseFeatureTestClass extends TestCase
{
    use DatabaseTransactions;

    /**
     * The user instance.
     *
     * @var User $userInstance
     */
    protected User $userInstance;

    /**
     * The base url for the api.
     *
     * @var string $baseUrl.
     */
    protected string $baseUrl;

    /**
     * Token for accessing authenticated routes.
     *
     * @var string $token
     */
    protected string $token;

    /**
     * Setup method.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseUrl();
        $this->createSuperUser();
        $this->setToken();
    }

    /**
     * Sets the base url for api testing.
     *
     * @return void
     */
    private function setBaseUrl()
    {
        $url = env('APP_URL', '');
        $this->assertNotEmpty($url);
        $this->baseUrl = $url . '/api';
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
     * Creates a token for the tests.
     *
     * @return void
     */
    private function setToken()
    {
        $this->token = (app(AuthService::class))
            ->login($this->userInstance->email, 'P4$$w0rd')->data['token'];
    }
}
