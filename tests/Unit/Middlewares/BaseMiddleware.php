<?php

namespace Tests\Unit\Middlewares;

use App\Http\Services\AuthService;
use App\Http\Traits\ApiResponse;
use App\Models\Role;
use App\Models\User;
use Closure;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Tests\TestCase;

class BaseMiddleware extends TestCase
{
    use DatabaseTransactions;
    use ApiResponse;

    /**
     * The user instance.
     *
     * @var User $userInstance
     */
    protected User $userInstance;

    /**
     * The request.
     *
     * @var Request $request
     */
    protected Request $request;

    /**
     * Next to be callled by middleware.
     *
     * @var Closure $next
     */
    protected Closure $next;

    /**
     * The request JWT Token
     *
     * @var string $token
     */
    protected $token;

    /**
     * Setup method.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->createSuperUser();
        $this->populateRequests();
        $this->populateNext();
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
     * Creates and populates the request for the controllers.
     *
     * @return void
     */
    private function populateRequests(): void
    {
        $user          = $this->userInstance;
        $this->request = Request::create('', 'POST', []);
        $this->request->setUserResolver(
            function () use ($user) {
                return $user;
            }
        );

        $token = (app(AuthService::class))
            ->login($this->userInstance->email, 'P4$$w0rd')->data['token'];

        $this->request->headers->set('Authorization', 'Bearer ' . $token);
    }

    /**
     * Populates the $next attribute.
     *
     * @return void
     */
    private function populateNext(): void
    {
        $this->next = function () {
            return $this->success();
        };
    }
}
