<?php
namespace Genius\Resources;

/**
 * Class ArtistsResource
 * @package Genius\Resources
 *
 * @see https://docs.genius.com/#artists-h2
 */
class ArtistsResource extends AbstractResource
{
    
    public function get($id, $text_format = 'dom')
    {
        return $this->sendRequest('GET', 'artists/' . $id . '/?' . http_build_query(['text_format' => $text_format]));
    }
    
    public function getSongs($id, array $data)
    {
        return $this->sendRequest('GET', 'artists/' . $id . '/songs/?' . http_build_query($data));
    }
    
}
