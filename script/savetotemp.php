<?php
$p = $_POST;

print_r($p);

include 'conn.php';

$datenow = date('Y-m-d');
$dateqty = $p['dateqty'];
$dateqty = date('Y-m-d',strtotime($dateqty));
$nextTuesday = date('Y-m-d',strtotime('next tuesday',$dateqty));

$mat_codes = $p['mat_code'];
$mat_qtys = $p['mat_qty'];
$keytype = $p['keytype'];
$mat_group = $p['mat_group'];
$mat_depart = $p['mat_depart'];
$reasonWaste = $p['reason_waste'];


if ($reasonWaste == 'undefined') {
	$reasonWaste = '';
}
$empcode = $p['empcode'];
$plant = $p['plant'];
$outletCode = $p['outletCode'];
$mattdesc = $p['mattdesc'];
$unitcode = $p['unitcode'];
$unitprice = $p['unitprice'];

if (($keytype == 'ending') || ($keytype == 'waste')) {

	if ($keytype == 'waste'){

	for ($i=0; $i < count($mat_qtys) ; $i++) {
		if ($mat_qtys[$i] != '') {
			$sql = "INSERT INTO matmgdb 
		([MAT_CODE],[LOSS_QTY],[PLANT],[SAVED_BY],[DOC_STATUS],[DOC_ID],[MAT_DEPART],[SYSTEM_DATE],[SAVED_DATE],[MAT_T_DESC],[UNIT_CODE],[UNIT_PRICE],[WASTE_REASON],[OUTLET_CODE],[MAT_GROUP])
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
			,'".$reasonWaste."'
			,'".$outletCode."'
			,'".$mat_group[$i]."'
			
			)";

			$sall .= $sql;
		}else {
			echo '!!Please Enter data again'.'<br>';
			return;
		}	
	}
	}
else {
	for ($i=0; $i < count($mat_qtys) ; $i++) {
			if ($mat_qtys[$i] != '') {
		$sql = "INSERT INTO matmgdb 
		([MAT_CODE],[ENDING_QTY],[BEGINING_QTY],[PLANT],[SAVED_BY],[DOC_STATUS],[DOC_ID],[MAT_DEPART],[SYSTEM_DATE],[SAVED_DATE],[MAT_T_DESC],[UNIT_CODE],[UNIT_PRICE],[WASTE_REASON],[OUTLET_CODE],[MAT_GROUP])
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
			,'".$reasonWaste."'
			,'".$outletCode."'
			,'".$mat_group[$i]."'
			
			)";

$sall .= $sql;
			}else {
			echo '!!Please Enter data again !';
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