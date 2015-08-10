<?php
namespace Genius;
class GeniusResponseHandler {
    public $response;
    public function __construct($response){
        $this->response = json_decode($response);
        if(isset($this->reponse->error)){
            Throw new \Genius\GeniusResponseHandlerException( "No reponse." );
        } else {
            $this->reponse = $this->response->response;
            if(isset($this->reponse->error)){
                Throw new \Genius\GeniusResponseHandlerException( $this->response->error_description );
            }     
            if(isset($this->reponse->status) && $this->response->status != '200'){
                Throw new \Genius\GeniusResponseHandlerException( $this->response->status );
            }
        }				return $this->response;
    }
}
class GeniusResponseHandlerException extends \Exception {
}