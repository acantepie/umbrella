<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

use Symfony\Component\HttpFoundation\JsonResponse;

class DataTableResponse extends JsonResponse
{
    public static function createError(string $error): self
    {
        return new self(['error' => $error], self::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function createSuccess(array $rowViews, int $count, int $draw): self
    {
        return new self([
            'draw' => $draw,
            'recordsTotal' => $count, // Total records, before filtering
            'recordsFiltered' => $count, // Total records, after filtering
            'data' => $rowViews,
        ]);
    }
}
