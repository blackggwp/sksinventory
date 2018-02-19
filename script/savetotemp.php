<?php
$g = $_GET;

print_r($g);

include 'conn.php';

$datenow = date('Y-m-d');
$dateqty = $g['dateqty'];
$dateqty = date('Y-m-d',strtotime($dateqty));
$nextTuesday = date('Y-m-d',strtotime('next tuesday',$dateqty));

$mat_codes = $g['mat_code'];
$mat_qtys = $g['mat_qty'];
$keytype = $g['keytype'];
$mat_group = $g['mat_group'];
$mat_depart = $g['mat_depart'];
$empcode = $g['empcode'];
$plant = $g['plant'];
$mattdesc = $g['mattdesc'];
$unitcode = $g['unitcode'];
$unitprice = $g['unitprice'];

if (($keytype == 'ending') || ($keytype == 'waste')) {

// 	$sqlDel=' delete from  matmg  where mat_code in (';
// 		for ($i=0; $i < count($mat_qtys) ; $i++) {
// 			$sqlDel.="'".$mat_codes[$i]."',";
// 		}
// 		$sqlDel=substr($sqlDel, 0, -1); 
// 		$sqlDel.=" ) AND SAVED_DATE = '".$dateqty."' ";
// try {
// 	$del = $conn->query($sqlDel);
// } catch (Exception $e) {

// }
// if ($del) {
// 	echo "Delete Success";
// 	echo "$sqlDel";
// }else {
// 	echo "Delete Failed";
// }

if ($keytype == 'waste'){

	for ($i=0; $i < count($mat_qtys) ; $i++) {
		if ($mat_qtys[$i] != '') {
			$sql = "INSERT INTO SKS_WEB.dbo.matmgdb 
		([MAT_CODE],[LOSS_QTY],[PLANT],[SAVED_BY],[DOC_STATUS],[DOC_ID],[MAT_DEPART],[SYSTEM_DATE],[SAVED_DATE],[MAT_T_DESC],[UNIT_CODE],[UNIT_PRICE])
		VALUES (
			'".$mat_codes[$i]."'
			,'".$mat_qtys[$i]."'
			,'".$plant."'
			,'".$empcode."'
			,'1'
			,'2'
			,'".$mat_depart[$i]."'
			,'".$datenow."'
			,'".$dateqty."'
			,'".$mattdesc[$i]."'
			,'".$unitcode[$i]."'
			,'".$unitprice[$i]."'
			)";

			$sall .= $sql;
		}else {
			echo '!!Please Enter data again';
			return;
		}	
	}
}
else {
	for ($i=0; $i < count($mat_qtys) ; $i++) {
			if ($mat_qtys[$i] != '') {
		$sql = "INSERT INTO SKS_WEB.dbo.matmgdb 
		([MAT_CODE],[ENDING_QTY],[BEGINING_QTY],[PLANT],[SAVED_BY],[DOC_STATUS],[DOC_ID],[MAT_DEPART],[SYSTEM_DATE],[SAVED_DATE],[MAT_T_DESC],[UNIT_CODE],[UNIT_PRICE])
		VALUES (
			'".$mat_codes[$i]."'
			,'".$mat_qtys[$i]."'
			,'".$mat_qtys[$i]."'
			,'".$plant."'
			,'".$empcode."'
			,'1'
			,'2'
			,'".$mat_depart[$i]."'
			,'".$datenow."'
			,'".$dateqty."'
			,'".$mattdesc[$i]."'
			,'".$unitcode[$i]."'
			,'".$unitprice[$i]."'
			)";

$sall .= $sql;
			}else {
			echo '!!Please Enter data again';
			return;
			}
	}
}

try {
			$ins = $conn->query($sall);  ///////// Execute sql insert data
		} catch (Exception $e) {
			echo "Error: ".$e;
		}

		echo "$sall";
		if ($ins) {
			echo "Insert QTY Success";
		}else {
			echo "cannot insert data";
		}

	}


	?>