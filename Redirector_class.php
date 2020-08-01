<?php


/**
 * Class Redirector
 *
 *
 */
class Redirector
{
    public $DB;

    public $debug_mode = true; // Не будет реального перехода

    public function __construct( &$DBC )
    {
        $this -> DB = $DBC;

    }

    public function Resolve_URI_Request( $uri )
    {
        //$this->DB -> Get_error();

        # $uri = "/empty_uri"  или  "/empty_uri/123/213"
        # Надо взять только 1 часть

        $uri = explode("/",$uri)[1];

        echo $uri;



    }



}






?>