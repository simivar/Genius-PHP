<?php
declare(strict_types=1);

namespace Genius\Resources;

use Genius\Authentication\Scope;
use stdClass;

/**
 * @see https://docs.genius.com/#account-h2
 */
final class AccountResource extends AbstractResource
{
    public function get(string $text_format = 'dom'): stdClass
    {
        $this->requireScope(Scope::SCOPE_ME);
        
        return $this->requester->get('account', ['text_format' => $text_format]);
    }
}
