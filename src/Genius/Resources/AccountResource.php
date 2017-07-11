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
    
    public function get($text_format = 'dom')
    {
        $this->requireScope(Scope::SCOPE_ME);
        
        return $this->sendRequest('GET', 'account/?' . http_build_query(['text_format' => $text_format]));
    }
    
}
