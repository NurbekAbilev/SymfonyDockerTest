<?php

namespace App\Queries;

use App\Queries\Contracts\UserQueryContract;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

final class UserQuery implements UserQueryContract
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

	public function getUsers(Request $request): array 
    {
        return $this->userRepository->findByEmailOrUsername($request->get('email'), $request->get('username'));
	}
}