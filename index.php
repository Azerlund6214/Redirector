<?php
	
    //TODO: Написать обработку ? в uri
    // В логи пишутся только успешные переходы
    // Можно добавить статус ссылки - активная и выключенная (дописать "where status=active" в sql)


    #$debug_mode = true; // Выводить все переменные и не делать реальный редирект в конце
    $debug_mode = false;

    $logging_active = true; # Писать ли логи в бд


    $db_host = '127.0.0.1';
    $db_user = 'root';
    $db_pass = 'root';
    $db_name = "redirector";

	
    require_once( "db_controller_mysqli.php" );
    require_once( "SF_CLASS.php" );

    #####

    $DBC = new DB_Controller();
    $DBC -> Connect( $db_host, $db_user , $db_pass );
    $DBC -> Select_db( $db_name );
    //$DBC -> Get_error();

    #####

    $request_uri = $_SERVER['REQUEST_URI'];

    if ( $request_uri === "/" )
        header("Location: ".SF::Get_This_Server_Domain()."/empty_uri" );

    #####

    $sql = "SELECT * FROM directions WHERE uri='$request_uri'";
    $result = $DBC -> Query($sql , "assoc");

    if ( count( $result) === 0 )
        header("Location: ".SF::Get_This_Server_Domain()."/uri_not_found" );

    if( $debug_mode ) SF::PRINTER($result);
    /*  [uri] => /uri_not_found
        [destination] => https://yandex.ru/
        [delay_ms] => 5
        [description] => Если не нашли uri в этой таблице  */

    #####

    if( $debug_mode ) echo "<br>Спим ".$result['delay_ms']."мс" ;

    usleep( $result['delay_ms'] );

    #####

    if( $logging_active )
    {
        $sql = "INSERT INTO logs (uri, destination )
                VALUES(  '".$request_uri."' , '".$result['destination'] ."' )";

        //echo "<br>".$sql; exit();

        if ($debug_mode) echo "<br>Пишем в лог => $sql";
        $DBC->Exec($sql);
    }

    #####

    if( $debug_mode ) exit( "<br>Сейчас был бы переход в => ".$result['destination']."<hr>Exit" ) ;


    header("Location: ".$result['destination'] );


	
?>