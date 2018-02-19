<?php
include ('conn.php');
$sql = "INSERT INTO matmgdb ([MAT_CODE]
      ,[MAT_QTY]
      ,[PLANT]
      ,[SAVED_BY]
      ,[SAVED_DATE]
      ,[DOC_STATUS]
      ,[DOC_ID]
      ,[MAT_GROUP]
      ,[UNIT_PRICE]
      ,[BEGINING_QTY]
      ,[ENDING_QTY]
      ,[LOSS_QTY]
      ,[SYSTEM_DATE]
      ,[MAT_DEPART]
      ,[D01]
      ,[D02]
      ,[D03]
      ,[D04]
      ,[D05]
      ,[D06]
      ,[D07]
      ,[YYMM]
      ,[D08]
      ,[D09]
      ,[D10]
      ,[D11]
      ,[D12]
      ,[D13]
      ,[D14]
      ,[D15]
      ,[D16]
      ,[D17]
      ,[D18]
      ,[D19]
      ,[D20]
      ,[D21]
      ,[D22]
      ,[D23]
      ,[D24]
      ,[D25]
      ,[D26]
      ,[D27]
      ,[D28]
      ,[D29]
      ,[D30]
      ,[D31])
SELECT [MAT_CODE]
      ,[MAT_QTY]
      ,[PLANT]
      ,[SAVED_BY]
      ,[SAVED_DATE]
      ,[DOC_STATUS]
      ,[DOC_ID]
      ,[MAT_GROUP]
      ,[UNIT_PRICE]
      ,[BEGINING_QTY]
      ,[ENDING_QTY]
      ,[LOSS_QTY]
      ,[SYSTEM_DATE]
      ,[MAT_DEPART]
      
FROM matmg
WHERE DOC_STATUS = '1';
DELETE FROM matmg WHERE DOC_STATUS = '1';
";
echo "$sql";
try {
	$move = $conn->query($sql);
	
} catch (Exception $e) {
	echo "$e";
}
if ($move) {
	echo "Save to DB complete";
}else{
	echo "Cannot Save Data To DB";
}

?>