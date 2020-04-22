<?php

namespace CheckpointPHP;

class Request
{
    /**
     * The Checkpoint class instance we belong to.
     *
     * @var \CheckpointPHP\CheckpointEmail
     */
    protected $_parent;
 
    /**
     * Which API version to use for this request.
     *
     * @var int
     */
    private $_url;
    private $_userAgent;
    private $_params = [];
    private $_posts = [];
    private $_puts = [];
    private $_delete = false;
    private $_headers = [];
    private $_debug = false;

    public function __construct(
        CheckpointEmail $parent,
        $url)
    {
        $this->_userAgent =  'Checkpoint/3.0 (PHP-SDK)';
        $this->_parent = $parent;
        $this->_url = $url;
        return $this;
    }

    

    public function addParam(
        $key,
        $value)
    {
        if ($value === true) {
            $value = 'true';
        }
        $this->_params[$key] = $value;
        return $this;
    }

    public function addPost(
        $key,
        $value)
    {
        $this->_posts[$key] = $value;
        return $this;
    }

    public function addPut(
        $key,
        $value)
    {
        $this->_puts[$key] = $value;
        return $this;
    }

    public function addDelete(
        $key,
        $value)
    {
        $this->_delete[$key] = $value;
        return $this;
    }

    public function setDebug(){
        $this->_debug = true;
        return $this;
    }

    
    public function addHeader(
        $key,
        $value)
    {
        $this->_headers[$key] = $value;
        return $this;
    }

    public function getResponse()
    {

        $ch = curl_init();

        if($this->_params){
            $this->_url = $this->_url . '?' . http_build_query($this->_params);
        }

        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->_userAgent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_ENCODING,  '');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Utils::convertHeaderCurl($this->_headers));

        if($this->_posts && empty($this->_postJson)){
            curl_setopt($ch, CURLOPT_POST, true);
            if($this->_isToken){
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->_posts));
            }
            else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->_posts));

            }

            if($this->_debug){
                print_r($this->_posts); die();
            }
        }

        if($this->_puts){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->_puts));

            if($this->_debug){
                print_r($this->_puts); die();
            }

        }


        if($this->_delete){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->_delete));

            if($this->_debug){
                print_r($this->_delete); die();
            }

        }

        $resp           = curl_exec($ch);
        $header_len     = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $curl_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE );
        $header         = substr($resp, 0, $header_len);
        $body           = substr($resp, $header_len);
        $body = json_decode($body, true);

        curl_close($ch);

        if($curl_http_code == 200) {
            
            return [
                'status' => 'ok',
                'body' => $body
            ];

        } else {
 
            return [
                'status' => 'fail',
                'http_code' => $curl_http_code,
                'header' => $header,
                'body' => $body
            ];
        }
    }
}