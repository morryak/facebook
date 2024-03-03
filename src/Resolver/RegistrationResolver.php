<?php

declare(strict_types=1);

namespace App\Resolver;

use App\DTO\RequestDto\RegistrationEntryDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class RegistrationResolver implements ValueResolverInterface
{

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== 'RegistrationEntryDto::class') {
            return [];
        }

        yield new RegistrationEntryDto('test', 'pass');
    }
}