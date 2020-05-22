<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use Illuminate\Database\Query\Builder;

interface EloquentRepository
{
    /**
     * Return model table query builder
     *
     * @return Builder
     */
    public function getTableQueryBuilder(): Builder;
}
