<?php
declare(strict_types=1);

namespace Genius\Resources;

use stdClass;

/**
 * @see https://docs.genius.com/#web_pages-h2
 */
class WebPagesResource extends AbstractResource
{
    public function get(string $raw_annotatable_url, ?string $canonical_url = null, ?string $og_url = null): stdClass
    {
        return $this->requester->get(
            'web_pages/lookup',
            [
                'raw_annotatable_url' => $raw_annotatable_url,
                'canonical_url' => $canonical_url,
                'og_url' => $og_url,
            ]
        );
    }
}
