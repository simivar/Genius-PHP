<?php

declare(strict_types=1);

namespace Genius\Resources;

use stdClass;

/**
 * @see https://docs.genius.com/#songs-h2
 */
final class SongsResource extends AbstractResource
{
    public function get(int $id, string $text_format = 'dom'): stdClass
    {
        return $this->requester->get(
            sprintf('songs/%s', $id),
            ['text_format' => $text_format]
        );
    }
}
