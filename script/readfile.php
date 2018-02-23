<?php
include 'conn.php';
print_r($_FILES)."<br>";
$plant = $_COOKIE['plant'];
$target_dir = "../uploads/";
$file_name = $_FILES['fileToUpload']['name'];
$file_size = $_FILES['fileToUpload']['size'];
$file_tmp =  $_FILES['fileToUpload']['tmp_name'];
$file_type=  $_FILES['fileToUpload']['type'];
$target_file = $target_dir . basename($file_name);
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
$uploadOk = 1;


if(isset($_POST["uploadSubmit"])) {
    if (($fileType == 'xls') OR ($fileType == 'xlsx')) {
    	$check = true;
    }else{
    	$check = false;
    }

    if($check !== false) {

        $uploadOk = 1;
    } else {
        echo "File is not an excel type"."<br>";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists."."<br>";
    $uploadOk = 0;
}
if ($_FILES["fileToUpload"]["size"] <= 0) {
    echo "Please choose file to upload."."<br>";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 3000000) {  //30MB
    echo "Sorry, your file is too large."."<br>";
    $uploadOk = 0;
}
// Allow certain file formats
if(($fileType != "xls" )&& ($fileType != "xlsx")) {
    echo "Sorry, only type excel are allowed."."<br>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded."."<br>";
// if everything is ok, try to upload file
}
else {

    $fname = $target_dir.uniqid().'.xls';
    if (move_uploaded_file($file_tmp, $fname)) 
    {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
       readExcel($fname,$conn,$plant);
        // phpfac();

    } else {
        echo "คุณยังไม่ได้ upload file."."<br>";
    }
}

// $table = printtable($objRec);

// echo $table;

function readExcel ($file,$connect,$plant){


   //*** Get Document Path ***//
    $strPath = realpath(basename(getenv($_SERVER["SCRIPT_NAME"]))); // C:/AppServ/www/myphp
    $OpenFile = "MyXls/MyCustomer.xls";
    //*** Create Exce.Application ***//
    $xlApp = new COM("Excel.Application");
    $xlBook = $xlApp->Workbooks->Open($strPath."/".$file);
    //$xlBook = $xlApp->Workbooks->Open(realpath($OpenFile));

    $xlSheet1 = $xlBook->Worksheets(1); 

            $i=11;
            $col =1;

       while(($xlSheet1->Cells->Item($i,1)->value != '') OR ($xlSheet1->Cells->Item($i+10,1)->value != ''))

        {
            $strSQL .= " INSERT INTO [tupload]( Material_ID, MatDescTh, PostDate, DocID, STO_ID, MOV_TYPE, QTY, UNIT, VENDOR,PLANT)
            VALUES ('".$xlSheet1->Cells->Item($i,1)."'".","."'".iconv('tis-620', 'UTF-8', $xlSheet1->Cells->Item($i,5))."'".","
                ."'".$xlSheet1->Cells->Item($i,8)."'".","."'".$xlSheet1->Cells->Item($i,9)."'".","
                ."'".$xlSheet1->Cells->Item($i,11)."'".","."'".$xlSheet1->Cells->Item($i,13)."'".","
                ."'".$xlSheet1->Cells->Item($i,14)."'".","."'".$xlSheet1->Cells->Item($i,16)."'".","
                ."'".$xlSheet1->Cells->Item($i,17)."'".","."'".$plant."'".");"."";
                $i++;
        
        }
//             $strSQL .= "INSERT INTO tstolog
//                       (Material, [Material Description], [Movement Type], [Posting Date], [Qty in Un# of Entry], [Unit of Entry], Plant);
// SELECT     Material_ID, MatDescTh, MOV_TYPE, CONVERT(datetime, PostDate, 103) AS PD, QTY, UNIT, PLANT
// FROM         tupload";
        $q = $connect->query($strSQL);
                if ($q) {
                    echo "Insert Success Row" ,$i,"<br>";

                }else{
                    echo "Error Row : ",$i,"<br>";
                }
echo "$strSQL";

    //*** Close & Quit ***//
    $xlApp->Application->Quit();
    $xlApp = null;
    $xlBook = null;
    $xlSheet1 = null;
            
}

function ex($sql){
        return $conn->prepare($sql);
}
function clean($string) {
   // $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
   
   // return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
   return $string;
}

function showArray($ar){
    echo "<pre>";
    print_r($ar);
    echo"</pre>";

}

function printtable($results){

    $tcolumn = $results->columnCount();

    $h='';
    $cols=array();
    for ($counter = 0; $counter < $tcolumn; $counter ++) {
        $meta = $results->getColumnMeta($counter);
        $h.='<th>'.$meta['name'].'</th>';

    }   

    $r='';
    foreach ($results as $dr) {

        $r.='<tr>';
        for ($i = 0; $i < $tcolumn; $i ++) {
            $val=str_replace(".00","",$dr[$i]);
            $r.='<td>'.$val.'</td>';
        }
        $r.='</tr>';
    }

    $h='<thead>'.$h.'</thead>';
    $s.='<table class="tblreport table table-hover">'.$h.$r;
    $s.='</table>';

    return $s;

 }

?>