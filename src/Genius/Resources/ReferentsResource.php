<?php

namespace Genius\Resources;

/**
 * Class ReferentsResource
 * @package Genius\Resources
 *
 * @see     https://docs.genius.com/#referents-h2
 */
class ReferentsResource extends AbstractResource
{
    
    public function get(array $data)
    {
        return $this->sendRequest('GET', 'referents/?' . http_build_query($data));
    }
    
}
