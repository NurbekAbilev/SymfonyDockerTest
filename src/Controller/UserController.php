<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Requests\UserRequestValidator;
use Doctrine\Persistence\ManagerRegistry;
use App\Queries\Contracts\UserQueryContract;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface as ValidatorValidatorInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user_index', methods: ['GET'])]
    public function index(Request $request, SerializerInterface $serializer, UserQueryContract $userQueryContract): Response
    {
        $users = $userQueryContract->getUsers($request);

        $data = $serializer->serialize(['data' => $users], 'json');

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/user', name: 'user_create', methods: ['POST'])]
    public function create(Request $request, ValidatorValidatorInterface $validator, ManagerRegistry $doctrine, UserRepository $userRepository): Response
    {
        $user = new User();
        $user->setEmail($request->get('email'));
        $user->setPassword($request->get('password'));
        $user->setUsername($request->get('username'));

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse([
                'errors' => $errorsString
            ], 418);
        }

        $userRepository->save($user, true);

        return new JsonResponse([
            'message' => 'User created',
            'data' => ['id' => $user->getId()]
        ]);
    }

    #[Route('/user/{userId}', name: 'user_update', methods: ['PUT'])]
    public function udpate(int $userId, Request $request, ValidatorValidatorInterface $validator, ManagerRegistry $doctrine, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($userId);
        $user->setEmail($request->get('email'));
        $user->setPassword($request->get('password'));
        $user->setUsername($request->get('username'));

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse([
                'errors' => $errorsString
            ], 418);
        }

        $userRepository->save($user, true);

        return new JsonResponse([
            'message' => 'User updated',
            'data' => ['id' => $user->getId()]
        ]);
    }

    #[Route('/user/{userId}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(int $userId, Request $request, ValidatorValidatorInterface $validator, ManagerRegistry $doctrine, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($userId);

        $userRepository->remove($user, true);

        return new JsonResponse([
            'message' => 'User deleted',
            'data' => null
        ]);
    }
}
