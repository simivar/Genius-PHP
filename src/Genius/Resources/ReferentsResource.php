<?php

namespace Genius\Resources;

/**
 * Class ReferentsResource
 * @package Genius\Resources
 *
 * @see https://docs.genius.com/#referents-h2
 */
class ReferentsResource extends AbstractResource
{
    public function get(
        ?int $created_by_id = null,
        ?int $song_id = null,
        ?int $web_page_id = null,
        string $text_format = 'dom',
        ?int $per_page = null,
        ?int $page = null
    ): \stdClass
    {
        return $this->sendRequest(
            'GET',
            'referents/?' . http_build_query([
                'created_by_id' => $created_by_id,
                'song_id' => $song_id,
                'web_page_id' => $web_page_id,
                'text_format' => $text_format,
                'per_page' => $per_page,
                'page' => $page,
            ])
        );
    }
}
