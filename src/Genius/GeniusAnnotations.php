<?php
namespace Genius;

class GeniusAnnotations {
    public $access_token;
    public $http_request;

    public function __construct($access_token, $http_request = false){
        $this->access_token = $access_token;
        if(!$http_request){
            $this->http_request =  new GeniusHttpRequest( $this->access_token );
        } else {
            $this->http_request = $http_request;
        }
    }

    /**
     * Gets an annotations data from Genius by it's ID.
     * Annotation data returned from the API includes both the substance of the annotation and the necessary information for displaying it in its original context.
     * @param integer $annotation_id ID of annotation you want to get.
     * @param string $text_format Format for text bodies related to the document. One or more of dom, plain, and html, separated by commas (defaults to dom).
     * @return object Annotation data from Genius.
     */
    public function get( $annotation_id, $text_format = 'dom' ){
        return json_decode(
            $this->http_request->get(
                '/annotations/' . $annotation_id, array(
                    'text_format' => $text_format
                )
            )
        );
    }
}