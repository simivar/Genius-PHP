<?php
declare(strict_types=1);

namespace Genius\Resources;

use Genius\Authentication\Scope;
use stdClass;

/**
 * @see https://docs.genius.com/#annotations-h2
 */
final class AnnotationsResource extends AbstractResource
{
    public function get(int $id, string $text_format = 'dom'): stdClass
    {
        return $this->requester->get(
            sprintf('annotations/%s', $id),
            ['text_format' => $text_format]
        );
    }

    public function post(array $annotation, array $referent, array $web_page): stdClass
    {
        $this->requireScope(Scope::SCOPE_CREATE_ANNOTATION);

        return $this->requester->post('annotations', [
            'annotation' => $annotation,
            'referent' => $referent,
            'web_page' => $web_page,
        ]);
    }

    public function put(int $id, array $annotation, array $referent, array $web_page): stdClass
    {
        $this->requireScope(Scope::SCOPE_MANAGE_ANNOTATION);

        return $this->requester->put(
            sprintf('annotations/%s', $id),
            [
                'annotation' => $annotation,
                'referent' => $referent,
                'web_page' => $web_page,
            ]
        );
    }

    public function delete(int $id): stdClass
    {
        $this->requireScope(Scope::SCOPE_MANAGE_ANNOTATION);

        return $this->requester->delete(sprintf('annotations/%s', $id));
    }

    public function upvote(int $id): stdClass
    {
        $this->requireScope(Scope::SCOPE_VOTE);

        return $this->requester->put(sprintf('annotations/%s/upvote', $id));
    }

    public function downvote(int $id): stdClass
    {
        $this->requireScope(Scope::SCOPE_VOTE);

        return $this->requester->put(sprintf('annotations/%s/downvote', $id));
    }

    public function unvote(int $id): stdClass
    {
        $this->requireScope(Scope::SCOPE_VOTE);

        return $this->requester->put(sprintf('annotations/%s/unvote', $id));
    }
}
