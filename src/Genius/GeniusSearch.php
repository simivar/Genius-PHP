<?php
namespace Genius;
class GeniusSearch {
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
    public function get( $query ){
        return json_decode(
            $this->http_request->get(
                '/search', array(
                    'q' => $query,
                )
            )
        );
    }
}