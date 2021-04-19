<?php

namespace Umbrella\CoreBundle\Component\DataTable\DTO;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DataTableResponse
 */
class DataTableResponse extends JsonResponse
{
    public static function createError(string $error): DataTableResponse
    {
        return new self(['error' => $error], self::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function createSuccess(array $rowViews = [], int $count, string $draw)
    {
        return new self([
            'draw' => $draw,
            'recordsTotal' => $count, // Total records, before filtering
            'recordsFiltered' => $count, // Total records, after filtering
            'data' => $rowViews,
        ]);
    }
}
