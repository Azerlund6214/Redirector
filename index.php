<?php
	
	//ob_implicit_flush(); ### Отключаем SAPI-буфер
	//ob_end_flush();
	
    //TODO: Написать обработку ? в uri

    $db_host = '127.0.0.1';
    $db_user = 'root';
    $db_pass = 'root';

    $db_name = "redirector";

	
    require_once( "db_controller_mysqli.php" );
    require_once( "SF_CLASS.php" );
    require_once( "Redirector_class.php" );



    $DBC = new DB_Controller();
    $DBC -> Connect( $db_host, $db_user , $db_pass );
    $DBC -> Select_db( $db_name );
    //$DBC -> Get_error();

    //echo "<pre>" , print_r( $DBC -> test_conn() ) , "</pre>"; exit("12134");




    $R = new Redirector( $DBC );









    if ( $_SERVER['REQUEST_URI'] === "/" )
        header("Location: ". SF::Get_This_Server_Domain()."/empty_uri"  );



    $R -> Resolve_URI_Request( $_SERVER['REQUEST_URI'] );





    exit("<hr>exit");
















	########################################################
	
	# Пришли данные для замены
	if ( isset( $_POST["txt_data"] ) )
	{
		
		if ( $_POST["txt_pass"] != $txt_rewrite_pass )
		{
			echo "<br><a href='$curr_file_url'>$curr_file_url</a>";
			echo "<br><a href='$curr_file_url_echoget'>$curr_file_url_echoget</a>";
			exit ("<br>Неверный пароль для замены текста - exit");
		}
		
		file_put_contents( $txt_name, $_POST["txt_data"] );
		echo "Текст заменен<br>";
		
		echo "<br><a href='$curr_file_url'>$curr_file_url</a>";
		echo "<br><a href='$curr_file_url_echoget'>$curr_file_url_echoget</a>";
		
		exit ("<br>exit");
		
	}
	
	###############################
	
	# Хочу вывести и поменять
	if (isset( $_GET["echoget"] ) )
	{
		/*echo "<hr><pre>";
		echo file_get_contents( $txt_name );
		echo "</pre><hr>Файл $txt_name выведен";
		
		*/
		echo "<hr color=red>";
		
		echo '
		
		<form action="" method="post">
		 <p><textarea type="text" name="txt_data" rows="30" cols="120">'.file_get_contents( $txt_name ).'</textarea></p>
		 <p>Пароль: <input type="text" name="txt_pass" /></p>
		 <p><input type="submit" /></p>
		</form>
		
		';
		
		exit;
	}
	
	########################################################
	
	/*
	if ( $_SERVER['REQUEST_URI'] === "/" )
	{
		echo "URI пуст - exit";
		exit;
	} */
	
	
	//echo $_SERVER['REQUEST_URI'];
	
	// Читаем файл в массив.
	$arr_strings = file( $txt_name );
	
	foreach ( $arr_strings as $line )
	{
		$buf = explode( "###" , $line );
		
		$key = @trim($buf[0]);
		$uri = @trim($buf[1]);
		$sleep_ms = @trim($buf[2]);
		$url_target = @trim($buf[3]);
		
		if ( $key === "SKIP" )
		{
			if ( $debug_mode ) 
				echo "URI пропущен -> $uri <br>";
			
			continue;
		}
		
		
		if ( $key === "URL" )
		{
			if ( $uri === $_SERVER['REQUEST_URI'] )
			{	
				
				if ( $debug_mode )
				{
					echo "URI найден -> $uri <br>";
					echo "Спим $sleep_ms мс <br>";
					usleep($sleep_ms);			
					echo "Был бы переход по адресу: $url_target";
					exit;
				}
				
				usleep($sleep_ms);				
				header("Location: $url_target" );
				exit; // навсяк
			}
			
		}
		
		if ( $key === "END" )
		{
			if ( $debug_mode )
			{
				echo "URI = $uri <br>";
				echo "END = Этого URI нет в файле -> был бы переход по дефолту end'а = $url_target";
				exit;
			}
			
			usleep($sleep_ms);				
			header("Location: $url_target" );
			exit;
		}
		
	}
	
	echo "<hr>Выход за пределы цикла - в файле не стоит END !!!!!";
	
	
	
	echo "<hr>123";
	
	
	
?>