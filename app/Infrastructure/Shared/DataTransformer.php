<?php
declare(strict_types=1);

namespace App\Infrastructure\Shared;

interface DataTransformer
{
    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * Return fields as keys
     *
     * @return array
     */
    public function fields(): array;
}
