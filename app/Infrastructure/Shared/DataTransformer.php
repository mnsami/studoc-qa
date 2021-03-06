<?php
declare(strict_types=1);

namespace App\Infrastructure\Shared;

interface DataTransformer
{
    /**
     * @return array
     */
    public function toArray(): array;
}
