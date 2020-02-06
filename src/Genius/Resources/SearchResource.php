<?php
declare(strict_types=1);

namespace Genius\Resources;

/**
 * @see https://docs.genius.com/#search-h2
 */
class SearchResource extends AbstractResource
{
    public function get(string $query): \stdClass
    {
        return $this->getMethod(
            'search',
            ['q' => $query]
        );
    }
}
