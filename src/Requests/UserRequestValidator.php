<?php

namespace App\Requests;
use ApiPlatform\Api\QueryParameterValidator\Validator\ValidatorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validation;

class UserRequestValidator
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function validateRequest()
    {
        $input = $this->requestStack->getCurrentRequest()->request->all();
        
        $constraint = new Collection([
            'email' => [new NotBlank(), new Email()],
            'username' => [new NotBlank()],
            'password' => [new NotBlank()],
        ]);
        
        $validator = Validation::createValidator();

        $errors = $validator->validate($input, $constraint);

        return $errors;
    }
}
