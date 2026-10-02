<?php

namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);
        $token = $user->createToken('Personal Access Token')->accessToken;

        return [
            'user' => $user,
            'access_token' => $token,
        ];
    }

    public function login(array $credentials): array
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->accessToken;

            return [
                'user' => $user,
                'access_token' => $token,
            ];
        }

        throw new \Exception('Invalid credentials');
    }

    public function logout($token): bool
    {
        $token->revoke();
        return true;
    }

    public function getUser($user): ?array
    {
        return $user ? $user->toArray() : null;
    }
}
