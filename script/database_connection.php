<?php
	class Database
	{

var $host = '192.168.0.205\APPWEBPRD';
var $user = 'sa';
var $pass = 'P@ssw0rd';
var $database = 'SKS_WEB';


	public $link;

	public function Database($host, $user, $pass, $database)
	{
		$this->host=$host;
		$this->user=$user;
		$this->pass=$pass;
		$this->database=$database;

	}

	public function connect(){
	
	try {

	// $conn = sqlsrv_connect( $hostname, $connectionInfo);
	$conn = new PDO("sqlsrv:server=$host;Database = $database;", $user, $pass);
   



	// $conn->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
	// $conn = mssql_connect('192.168.0.235','sa','sukishi20272027');
	// if(!$connmssql) {
	// 	die('Could not connect:'.mssql_error());
	// }
	
} catch (Exception $e) {
	echo "Cannot connect DB : ".$e->getMessage();
}
}
	}
	?>