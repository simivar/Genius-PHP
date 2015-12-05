<?php
namespace Genius;

class GeniusWebpages {
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

    public function get( $raw_annotatable_url, $canonical_url = null, $og_url = null){
        return json_decode(
            $this->http_request->get(
                '/web_pages/lookup', array(
                    'raw_annotatable_url' => $raw_annotatable_url,
                    'canonical_url' => $canonical_url,
                    'og_url' => $og_url,
                )
            )
        );
    }
}