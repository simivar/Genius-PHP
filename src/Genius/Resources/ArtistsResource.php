<?php
declare(strict_types=1);

namespace Genius\Resources;

use stdClass;

/**
 * @see https://docs.genius.com/#artists-h2
 */
final class ArtistsResource extends AbstractResource
{
    public function get(int $id, string $text_format = 'dom'): stdClass
    {
        return $this->requester->get(
            sprintf('artists/%s', $id),
            ['text_format' => $text_format]
        );
    }
    
    public function getSongs(int $id, string $sort = 'title', ?int $per_page = null, ?int $page = null): stdClass
    {
        $data = [
            'sort' => $sort,
            'per_page' => $per_page,
            'page' => $page
        ];

        return $this->requester->get(
            sprintf('artists/%s/songs', $id),
            $data
        );
    }
}
