<?php

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
    
    public function get($id, $text_format = 'dom')
    {
        return $this->sendRequest('GET', 'annotations/' . $id . '/?' . http_build_query(['text_format' => $text_format]));
    }
    
    public function post(array $data)
    {
        $this->requireScope(Scope::SCOPE_CREATE_ANNOTATION);
        
        return $this->sendRequest('POST', 'annotations/', [], $data);
    }
    
    public function put($id, array $data)
    {
        $this->requireScope(Scope::SCOPE_MANAGE_ANNOTATION);
    
        return $this->sendRequest('PUT', 'annotations/' . $id . '/', [], $data);
    }
    
    public function delete($id)
    {
        $this->requireScope(Scope::SCOPE_MANAGE_ANNOTATION);
    
        return $this->sendRequest('DELETE', 'annotations/' . $id . '/');
    }
    
    public function upvote($id)
    {
        $this->requireScope(Scope::SCOPE_VOTE);
    
        return $this->sendRequest('PUT', 'annotations/' . $id . '/upvote/');
    }
    
    public function downvote($id)
    {
        $this->requireScope(Scope::SCOPE_VOTE);
    
        return $this->sendRequest('PUT', 'annotations/' . $id . '/downvote/');
    }
    
    public function unvote($id)
    {
        $this->requireScope(Scope::SCOPE_VOTE);
    
        return $this->sendRequest('PUT', 'annotations/' . $id . '/unvote/');
    }
    
}
