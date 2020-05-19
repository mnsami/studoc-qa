<?php
declare(strict_types=1);

namespace App\Infrastructure\Shared;

class EmptyDto implements DataTransformer
{

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function fields(): array
    {
        return [];
    }
}
