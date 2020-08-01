<?php

	class DB_Controller
	{

		public $db; # Главное подключение к бд

		
		####################################
		
		
		public function Get_connection()
		{
			return $this -> db;
		}
		
		public function __construct()
		{	//exit("123");
		}
		

		
		
		# Создание подключения
		public function Connect( $host , $user , $pass  )
		{
			
			// MySQLi, процедурная часть
			//$mysqli = mysqli_connect('localhost','username','password','database');

			// MySQLi, ООП
			$mysqli = new mysqli( $host , $user , $pass );

			
			if ($mysqli->connect_errno)
			{
				echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
				exit;
			}
			
			$mysqli->set_charset( "utf8" );
			
			
			$this -> db = $mysqli;
			

				
			//echo $mysqli->host_info . "\n";
			
			return true;
		}


		// не тестил
		public function Disconnect( )
		{
			$this->db->close();
		}
		
		# Вывести последнюю ошибку mysqli
		public function Get_error( )
		{
			if ( $this->db->errno != 0 )
				echo "Get_error => (№" . $this->db->errno . ") " . $this->db->error;
			else
				echo "Get_error => Ошибок нет";
		}
		
		
		// Не предполагает выачу результатов
		public function Exec( $sql )
		{
			$this->db -> query( $sql );
		
		}
		
		
		public function Query( $sql , $fetch_type = "all" )
		{
			
			$result = $this->db -> query( $sql );
			//printf("Select вернул ". $result->num_rows ." строк.");
			
			
			switch( $fetch_type )
			{
				case "all":   $fetched = $result -> fetch_all();  break;
				case "assoc": $fetched = $result -> fetch_assoc(); break;
				
				default: exit("Невалидный fetch_type");
			}
			
			
			/* удаление выборки */
			$result->free();
			
			
			return $fetched;
		}
		
		
		
		
		
		// робит
		public function Select_db( $target_db )
		{
			$this->db -> query("USE $target_db");
		}
		
		
		# Проверка работоспособности.
		function test_conn()
		{

			echo $this->Query('SELECT VERSION()')[0][0];
				
			
			exit;
		
		}
		
		
		
		
		/*
			$query = $mysqli->prepare('
			SELECT * FROM users
			WHERE username = ?
			AND email = ?
			AND last_login > ?');
			  
			$query->bind_param('sss', 'test', $mail, time() - 3600);
			$query->execute();
			
			
			
// MySQLi, ООП
if ($result = $mysqli->query($query))
{
   while ($user = $result->fetch_object('User'))
   {
      echo $user->info()."\n";
   }
}
			
			
			
			
// MySQLi, "ручная" зачистка параметра
$username = mysqli_real_escape_string($_GET['username']);
$mysqli->query("SELECT * FROM users WHERE username = '$username'");

// mysqli, подготовленные выражения
$query = $mysqli->prepare('SELECT * FROM users WHERE username = ?');
$query->bind_param('s', $_GET['username']);
$query->execute();

		*/
		
		
		

	} # End class
	
?>