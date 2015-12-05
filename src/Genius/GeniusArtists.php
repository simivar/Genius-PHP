<?php
namespace Genius;
class GeniusArtists {
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
     * An artist is how Genius represents the creator of one or more songs (or other documents hosted on Genius). It's usually a musician or group of musicians.
     * @param integer $artist_id Artist's ID
     * @param string $text_format Format for text bodies related to the document. One or more of dom, plain, and html, separated by commas (defaults to dom).
     * @return object Object with artist data
     */
    public function get($artist_id, $text_format = 'dom'){
        return json_decode(
            $this->http_request->get(
                '/artists/' . $artist_id, array(
                    'text_format' => $text_format
                )
            )
        );
    }

    /**
     * Documents (songs) for the artist specified. By default, 20 items are returned for each request.
     * @param integer $artist_id ID of the artist. 
     * @param integer $per_page Number of results to return per request 
     * @param integer $page Paginated offset, (e.g., per_page=5&page=3 returns songs 11–15)
     * @param string $sort title (default) or popularity
     */
    public function getSongs( $artist_id, $per_page = 20, $page = 1, $sort = 'title' ){
        return json_decode(
            $this->http_request->get(
                '/artists/' . $artist_id . '/songs', array(
                    'page' => $page,
                    'per_page' => $per_page,
                    'sort' => $sort,
                )
            )
        );
    }
}