<?php
date_default_timezone_set('Asia/Bangkok');
$hostname = '192.168.0.205\APPWEBPRD';
$username = 'sa';
$password = 'P@ssw0rd';
$database = 'SKS_WEB';

	// $dsn = "odbc:DRIVER={SQL Server};SERVER=$hostname;DATABASE=$database;charset=UTF-8";
	// $connectionInfo = array( "Database"=>$database, "CharacterSet" =>"UTF-8"); 

try {

	// $conn = sqlsrv_connect( $hostname, $connectionInfo);
	$conn = new PDO("sqlsrv:server=$hostname;Database = $database;", $username, $password);
   



	// $conn->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
	// $conn = mssql_connect('192.168.0.235','sa','sukishi20272027');
	// if(!$connmssql) {
	// 	die('Could not connect:'.mssql_error());
	// }
	
} catch (Exception $e) {
	echo "Cannot connect DB : ".$e->getMessage();
}




	
?>