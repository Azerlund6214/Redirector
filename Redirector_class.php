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

    public $income_uri;




    public function __construct( &$DBC )
    {
        $this -> DB = $DBC;
    }

    public function Resolve_URI_Request( $uri )
    {
        $this->income_uri = $uri;

        //$this->DB -> Get_error();

        # $uri = "/empty_uri"  или  "/empty_uri/123/213"
        # Надо взять только 1 часть
        //$uri = explode("/",$uri)[1];

        // status uri destination sleep description
        //echo $uri;

        $sql = "SELECT * FROM directions WHERE uri='$uri'";
        $result = $this->DB -> Query($sql);

        if ( count( $result) === 0 )
            $this->Redirect_to( SF::Get_This_Server_Domain()."/uri_not_found" );


        //SF::PRINTER( count( $result) );

        SF::PRINTER($result);



    }


    public function Redirect_to( $destination )
    {

        # Тут будет запись в логи
        #и сон


        header("Location: $destination" );


    }


}






?>