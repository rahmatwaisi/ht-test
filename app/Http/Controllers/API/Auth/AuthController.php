<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Interfaces\Auth\LoginServiceInterface;
use App\Services\Interfaces\Auth\LogoutServiceInterface;
use App\Services\Interfaces\Auth\RegisterServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private LoginServiceInterface $loginService;
    private LogoutServiceInterface $logoutService;
    private RegisterServiceInterface $registerService;

    public function __construct(
        LoginServiceInterface    $loginService,
        LogoutServiceInterface   $logoutService,
        RegisterServiceInterface $registerService,
    )
    {
        $this->loginService = $loginService;
        $this->logoutService = $logoutService;
        $this->registerService = $registerService;
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->registerService->register($request->validated());

        return response()->json([
            'result' => ['message' => 'User registered successfully']
        ], Response::HTTP_CREATED);
    }


    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->loginService->login($request->validated());
        return response()->json([
            'result' => [
                'access_token' => $token, 'token_type' => 'Bearer'
            ]
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $result = $this->logoutService->logout();
        return response()->json([
            'result' => [
                'message' => $result ? __('auth.logout_success') : __('auth.logout_failed')
            ]
        ]);
    }
}
