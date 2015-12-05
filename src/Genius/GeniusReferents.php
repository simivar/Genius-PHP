<?php
namespace Genius;

class GeniusReferents {
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
     * Gets referents for specified object (song/webpage) by it's ID.
     * When a new annotation is created either a referent is created with it or that annotation is attached to an existing referent.
     * You may pass only one of song_id and web_page_id, not both.
     * @param integer $song_id ID of a web page to get referents for 
     * @param integer $web_page_id ID of webpage for which you want to get referents.
     * @param integer $created_by_id ID of a user to get referents for 
     * @param integer $page Paginated offset, (e.g., per_page=5&page=3 returns songs 11–15)
     * @param integer $per_page Number of results to return per request
     * @param string $text_format Format for text bodies related to the document. One or more of dom, plain, and html, separated by commas (defaults to dom).
     * @return object Object with all the data for specified referent
     */
    public function get( $song_id = null, $web_page_id = null, $created_by_id = null, $page = 1, $per_page = 10, $text_format = 'dom' ){
        if( (!is_null($songs_id) && !is_null($web_page_id)) || (is_null($song_id) && is_null($web_page_id)) ){
            throw new GeniusReferentsException("You need to set one of required IDs.");
        }
        return json_decode(
            $this->http_request->get(
                '/referents', array(
                    'created_by_id' => $created_by_id,
                    'song_id' => $song_id,
                    'web_page_id' => $web_page_id,
                    'page' => $page,
                    'per_page' => $per_page,
                    'text_format' => $text_format,
                )
            )
        );
    }
}

class GeniusReferentsException extends \Exception {  
}