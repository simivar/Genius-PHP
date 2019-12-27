<?php
namespace Genius\Resources;

use Genius\Authentication\Scope;

/**
 * Class ArtistsResource
 * @package Genius\Resources
 *
 * @see https://docs.genius.com/#account-h2
 */
class AccountResource extends AbstractResource
{
    /**
     * @param string $text_format
     * @return \stdClass
     * @throws ResourceException
     */
    public function get(string $text_format = 'dom'): \stdClass
    {
        $this->requireScope(Scope::SCOPE_ME);
        
        return $this->sendRequest('GET', 'account/?' . http_build_query(['text_format' => $text_format]));
    }
}
