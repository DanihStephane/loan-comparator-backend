<?php

namespace App\Traits;

use App\Core\Application\Factory\ApiResponseFactory;
use App\Core\Domain\ValueObject\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait RequestValidationTrait
{
    /**
     * Validates a request object using the Symfony validator
     *
     * @param object             $request   The request object to validate
     * @param ValidatorInterface $validator The validator service
     *
     * @return ApiResponse|null Returns an error response if validation fails, null otherwise
     */
    protected function validateRequest(object $request, ValidatorInterface $validator): ?ApiResponse
    {
        $violations = $validator->validate($request);

        if (0 !== $violations->count()) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return ApiResponseFactory::createError(
                code: 'VALIDATION_ERROR',
                message: 'Validation failed',
                errors: $errors,
                status: Response::HTTP_BAD_REQUEST
            );
        }

        return null;
    }
}
