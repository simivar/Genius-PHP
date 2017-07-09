<?php
namespace Genius\Resources;

/**
 * Class ArtistsResource
 * @package Genius\Resources
 *
 * @see https://docs.genius.com/#search-h2
 */
class SearchResource extends AbstractResource
{
    
    public function get($query)
    {
        return $this->sendRequest('GET', 'search/?' . http_build_query(['q' => $query]));
    }
    
}
