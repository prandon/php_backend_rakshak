<?php
	class DbConnect{
		private $con;

		function __construct(){
			
		}

		function connect(){
			require_once dirname(__FILE__).'/Constants.php';
			$this->con = new mysqli(DB_HOST,DB_USERNAME,DB_PASS, DB_NAME);

			if(mysqli_connect_errno()){
				echo "Failed to connect".mysqli_connect_errno();
			}

			return $this->con;
		}
	}
?>