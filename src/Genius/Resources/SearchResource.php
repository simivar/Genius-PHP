<?php
declare(strict_types=1);

namespace Genius\Resources;

use stdClass;

/**
 * @see https://docs.genius.com/#search-h2
 */
final class SearchResource extends AbstractResource
{
    public function get(string $query): stdClass
    {
        return $this->requester->get(
            'search',
            ['q' => $query]
        );
    }
}
