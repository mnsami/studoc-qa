<?php
declare(strict_types=1);

namespace App\Exceptions;

use App\Infrastructure\Shared\DataTransformer;

abstract class KernelException extends \Exception implements DomainException
{
    /**
     * @inheritDoc
     */
    public function getErrorResponse(): DataTransformer
    {
        return new ErrorExceptionDto($this);
    }
}
