<?php
namespace Genius;
class GeniusSongs {
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
     * A song is a document hosted on Genius. It's usually music lyrics.
     * Data for a song includes details about the document itself and information about all the referents that are attached to it, including the text to which they refer.
     * @param integer $song_id ID of the song
     * @param string $text_format Format for text bodies related to the document. One or more of dom, plain, and html, separated by commas (defaults to dom).
     * @return object Object with song data
     */
    public function get( $song_id, $text_format = 'dom' ){
        return json_decode(
            $this->http_request->get(
                '/songs/' . $song_id, array(
                    'text_format' => $text_format
                )
            )
        );
    }
}