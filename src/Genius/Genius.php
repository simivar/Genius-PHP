<?php
namespace Genius;
class Genius {
    public $access_token;
    public $http_request;
    public function __construct($access_token){
        $this->access_token = $access_token;
        $this->http_request = new GeniusHttpRequest( $this->access_token );
    }
    public function __get( $name ){
        if(isset($this->{$name})){
            return $this->{$name};
        }
        switch($name){
            case 'annotations':
            case 'referents':
            case 'songs':
            case 'artists':
            case 'webpages':
            case 'search':
            case 'account':
                $class = '\Genius\Genius' . ucfirst($name);
                $this->{$name} = new $class( $this->access_token, $this->http_request );
                return $this->{$name};
            break;
            default:
                throw new GeniusException("Wrong class name.");
            break;
        }
    }
}
class GeniusException extends \Exception {
}