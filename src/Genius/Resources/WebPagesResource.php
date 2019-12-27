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
    public function get(string $raw_annotatable_url, ?string $canonical_url = null, ?string $og_url = null): \stdClass
    {
        return $this->sendRequest(
            'GET',
            'web_pages/lookup/?' . http_build_query([
                'raw_annotatable_url' => $raw_annotatable_url,
                'canonical_url' => $canonical_url,
                'og_url' => $og_url,
            ])
        );
    }
}
