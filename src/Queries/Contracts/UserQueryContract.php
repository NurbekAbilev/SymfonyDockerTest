<?php

namespace App\Queries\Contracts;
use Symfony\Component\HttpFoundation\Request;

interface UserQueryContract
{
    public function getUsers(Request $request): array;
}