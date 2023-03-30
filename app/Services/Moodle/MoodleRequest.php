<?php

namespace App\Services\Moodle;
use GuzzleHttp\Client;

class MoodleRequest
{
    // private string $ws_function;
    // private string $ws_token;
    // private string $format = "json";
    // private array $params;
    /**
     * 
     * Create request to your own moodle webservice rest api. Бля такая параша поидеи АИТУ, пацаны не поступайте сюда
     * 
     * @param string $ws_token secret key of user
     * @param string $ws_function wsfunction of request
     * @param string $format in which format return the result. May be xml, json
     * @param array $params parametres of request. Like userid, courseid ant etc.
     * 
     */
    public function __construct(        
        private string $ws_function,
        private string $ws_token,
        private array $params,
        private string $format = "json"
    ){
        $this->params['wsfunction'] = $ws_function;
        $this->params['wstoken'] = $ws_token;
        $this->params['moodlewsrestformat'] = $format;
        $this->params = ['query' => $this->params];
    }
    /** 
     * 
     * Send request
     * 
     */
    public function send()
    {
        $client = new Client();    
        $res = $client->request('GET', config("moodle.webservice_url"), $this->params);
        return $res->getBody()->getContents();
    }


}


?>