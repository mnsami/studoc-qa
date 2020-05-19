<?php
declare(strict_types=1);

namespace App\Exceptions;

use App\Infrastructure\Shared\DataTransformer;

interface DomainException extends \Throwable
{
    /**
     * Return error as DTO
     *
     * @return DataTransformer
     */
    public function getErrorResponse(): DataTransformer;
}
