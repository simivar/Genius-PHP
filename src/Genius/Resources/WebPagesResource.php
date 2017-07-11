<?php
namespace Genius\Resources;

/**
 * Class ArtistsResource
 * @package Genius\Resources
 *
 * @see https://docs.genius.com/#web_pages-h2
 */
class WebPagesResource extends AbstractResource
{
    
    public function get(array $data)
    {
        return $this->sendRequest('GET', 'web_pages/lookup/?' . http_build_query($data));
    }
    
}
