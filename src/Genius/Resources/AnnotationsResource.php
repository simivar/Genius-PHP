<?php
declare(strict_types=1);

namespace Genius\Resources;

use Genius\Authentication\Scope;

/**
 * Class AnnotationsResource
 * @package Genius\Resources
 *
 * @see https://docs.genius.com/#annotations-h2
 */
class AnnotationsResource extends AbstractResource
{
    public function get(int $id, string $text_format = 'dom'): \stdClass
    {
        return $this->sendRequest('GET', 'annotations/' . $id . '/?' . http_build_query(['text_format' => $text_format]));
    }

    /**
     * @param array $annotation
     * @param array $referent
     * @param array $web_page
     * @return \stdClass
     * @throws ResourceException
     */
    public function post(array $annotation, array $referent, array $web_page): \stdClass
    {
        $this->requireScope(Scope::SCOPE_CREATE_ANNOTATION);
        
        return $this->sendRequest('POST', 'annotations/', [], [
            'annotation' => $annotation,
            'referent' => $referent,
            'web_page' => $web_page,
        ]);
    }

    /**
     * @param int $id
     * @param array $annotation
     * @param array $referent
     * @param array $web_page
     * @return \stdClass
     * @throws ResourceException
     */
    public function put(int $id, array $annotation, array $referent, array $web_page): \stdClass
    {
        $this->requireScope(Scope::SCOPE_MANAGE_ANNOTATION);
    
        return $this->sendRequest('PUT', 'annotations/' . $id . '/', [], [
            'annotation' => $annotation,
            'referent' => $referent,
            'web_page' => $web_page,
        ]);
    }

    /**
     * @param int $id
     * @return \stdClass
     * @throws ResourceException
     */
    public function delete(int $id): \stdClass
    {
        $this->requireScope(Scope::SCOPE_MANAGE_ANNOTATION);
    
        return $this->sendRequest('DELETE', 'annotations/' . $id . '/');
    }

    /**
     * @param int $id
     * @return \stdClass
     * @throws ResourceException
     */
    public function upvote(int $id): \stdClass
    {
        $this->requireScope(Scope::SCOPE_VOTE);
    
        return $this->sendRequest('PUT', 'annotations/' . $id . '/upvote/');
    }

    /**
     * @param int $id
     * @return \stdClass
     * @throws ResourceException
     */
    public function downvote(int $id): \stdClass
    {
        $this->requireScope(Scope::SCOPE_VOTE);
    
        return $this->sendRequest('PUT', 'annotations/' . $id . '/downvote/');
    }

    /**
     * @param int $id
     * @return \stdClass
     * @throws ResourceException
     */
    public function unvote(int $id): \stdClass
    {
        $this->requireScope(Scope::SCOPE_VOTE);
    
        return $this->sendRequest('PUT', 'annotations/' . $id . '/unvote/');
    }
}
