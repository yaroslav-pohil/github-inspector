<?php

declare(strict_types=1);

namespace App\Services\Github\Responses;

use Exception;

use function Clue\StreamFilter\fun;

class ResponseFactory
{
    /**
     * @param string $responseClass Class that extents AbstractResponse.
     * @param array[] $items List of array items.
     *
     * @return AbstractResponse[] List of response objects
     * @throws Exception
     */
    public static function buildMany(string $responseClass, array $items): array
    {
        if (!is_subclass_of($responseClass, AbstractResponse::class)) {
            throw new Exception('ResponseFactory can work only with instance of ' . AbstractResponse::class);
        }

        return array_map(function(array $itemData) use ($responseClass) {
            return new $responseClass($itemData);
        }, $items);
    }
}
