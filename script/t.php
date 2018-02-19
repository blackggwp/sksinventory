<?php
include 'conn.php';




   $strPath = realpath(basename(getenv($_SERVER["SCRIPT_NAME"]))); // C:/AppServ/www/myphp
    $OpenFile = "w1.xls";
    $xlApp = new COM("Excel.Application");
    $xlBook = $xlApp->Workbooks->Open($strPath."/".$OpenFile);
    $xlSheet1 = $xlBook->Worksheets(1); 

            $i=1;


       while(($xlSheet1->Cells->Item($i,1)->value != '') OR ($xlSheet1->Cells->Item($i+10,1)->value != '')){

 $itemname=$xlSheet1->Cells->Item($i,5);
    echo $itemname,'<br>';



$sql="insert into tupload(MatDescTh) values(:MatDescTh) ";
$a=array();
$a["MatDescTh"]=iconv('tis-620', 'utf-8', $itemname);

 $cmd= $conn->prepare($sql);
 $ret=$cmd->execute($a);



    $i++;

       }





/*
$sql="insert into tupload(MatDescTh) values(:MatDescTh) ";
$a=array();
$a["MatDescTh"]="xxxxxx";

 $cmd= $conn->prepare($sql);
 $ret=$cmd->execute($a);
*/

?>