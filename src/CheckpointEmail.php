<?php

namespace CheckpointPHP;



class CheckpointEmail {


    /**
    * Rest API
    *
    * @var string
    **/
    protected $_api = 'https://checkpoint.email/api/v3';


    /**
    * API Key
    *
    * @var string
    **/
    protected $_key;


    /**
    *
    * @var string
    *          token
    **/
    public function __construct($key){


        if(empty($key)) throw new Exception("Empty $key in __construct");

        $this->_key = $key;



    }



    private function verify($email){

        $_url = $this->_api . '/email-verifier'; 

        $_request = $this->request($_url)
                            ->addHeader('check-key', $this->_key)
                            ->addParan('email', $email)
                            ->getResponse();


        return $_request;
        
    }


    
    /**
    *
    * Used internally, but can also be used by end-users if they want
    * to create completely custom API queries without modifying this library.
    *
    * @param string $url
    *
    * @return array
    */
    public function request($url)
    {
        return new Request($this, $url);
    }
    

 
    

}