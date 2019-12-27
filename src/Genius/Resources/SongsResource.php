<?php

namespace Genius\Resources;

/**
 * Class SongsResource
 * @package Genius\Resources
 *
 * @see https://docs.genius.com/#songs-h2
 */
class SongsResource extends AbstractResource
{
    public function get(int $id, string $text_format = 'dom'): \stdClass
    {
        return $this->sendRequest('GET', 'songs/' . $id . '/?' . http_build_query(['text_format' => $text_format]));
    }
}
