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
    
    public function get($id, $text_format = 'dom')
    {
        return $this->sendRequest('GET', 'songs/' . $id . '/?' . http_build_query(['text_format' => $text_format]));
    }
    
}
