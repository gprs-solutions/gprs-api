<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;

class AuthService
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
     * Tries to log a user in.
     *
     * @param string $email    The user's email.
     * @param string $password The user's password.
     *
     * @return ServiceResult The result of the operation.
     */
    public function login(string $email, string $password): ServiceResult
    {
        try {
            $user           = $this->model->where('email', $email)->firstOrFail();
            $hashedPassword = $user->password;
            if (!Hash::check($password, $hashedPassword)) {
                Log::info(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] User ' . $user . ' failed to login.');
                return ServiceResult::failure(__('messages.unauthorized'));
            }

            $tokenResult = $this->generateToken($user->id);

            if (null === $tokenResult->data) {
                Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Failed to generate token');
                return ServiceResult::failure(__('messages.tokenFailure'));
            }

            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . ']  User ' . $user . ' logged in succesfully.');

            return $tokenResult;
        } catch (Exception $e) {
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }
    }

    /**
     * Generates a JWT token for an user.
     *
     * @param int $userId The user to get the token.
     *
     * @return ServiceResult The result of the operation.
     */
    private function generateToken(int $userId): ServiceResult
    {
        try {
            $expiration = env('JWT_EXPIRY', 3600);
            $issuedAt   = time();
            $expiresAt  = ($issuedAt + $expiration);

            $payload = [
                'sub' => $userId,
                'iat' => $issuedAt,
                'exp' => $expiresAt,
            ];
            $secret  = env('JWT_SECRET', '');
            $algo    = env('JWT_ALGO', '');

            if (empty($secret) || empty($algo)) {
                throw new Exception(__('messages.invalidJWTVars'));
            }

            $token = [
                'token' => JWT::encode($payload, $secret, $algo),
            ];
        } catch (Exception $e) {
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return ServiceResult::failure(__('messages.failedOperation'));
        }

        return ServiceResult::success(data: $token);
    }
}
