<?php
  $ck = $_COOKIE;
  $g = $_GET;
  include 'conn.php';
//   print_r($g);
// check empcode
$checkEmpcode = $conn->prepare("SELECT nUserID FROM imp_emp2 WHERE (nUserID = ? )");  
$checkEmpcode->bindParam(1, $g['empcode']);
$checkEmpcode->execute();
$rowCount = $checkEmpcode->rowCount();
// var_dump($rowCount);
$dx=array();
if( $rowCount != 0 ) {
    $dx["res"] = 'foundedUserID';
  }
  else {
    $dx["res"] = 'notFoundedUserID';
  }
  $json=json_encode($dx);
  echo $json;
  exit;
  return;
?>