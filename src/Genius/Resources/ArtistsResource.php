<?php
declare(strict_types=1);

namespace Genius\Resources;

/**
 * Class ArtistsResource
 * @package Genius\Resources
 *
 * @see https://docs.genius.com/#artists-h2
 */
class ArtistsResource extends AbstractResource
{
    public function get(int $id, string $text_format = 'dom'): \stdClass
    {
        return $this->sendRequest('GET', 'artists/' . $id . '/?' . http_build_query(['text_format' => $text_format]));
    }
    
    public function getSongs(int $id, string $sort = 'title', ?int $per_page = null, ?int $page = null): \stdClass
    {
        $data = [
            'sort' => $sort,
            'per_page' => $per_page,
            'page' => $page
        ];
        return $this->sendRequest('GET', 'artists/' . $id . '/songs/?' . http_build_query($data));
    }
}
