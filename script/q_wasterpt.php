<?php
include 'conn.php';
// adjust the memory allocation
// ini_set('memory_limit', '512M');
$d = $_GET;
$datestart = $d['datestart'];
$dateend = $d['dateend'];
$datestart = date('Y-m-d',strtotime($datestart));
$dateend = date('Y-m-d',strtotime($dateend));

// echo "$datestart...$dateend";
$plant = $d['plant'];

for ($i=$datestart; $i <=$dateend  ; $i++) {

	$d = $i;
	$d = '['.$i.'],';
	$dd .= $d; 

	$d2 = $i;
	$d2 = substr($d2, 8); 

	$d = substr($d, 0,-1);
	$d3 .= 'CONVERT(nvarchar,CAST('.$d.' AS numeric(18,1))) AS '.'D'.$d2.',';
	
	// remove condition for provide -sign
	// $h .= '(sum(cast(['.$i.'] as float)) > 0) OR ';
	$total .= "CAST(ISNULL(dbo.temp_cal_waste.[$i],0) AS float)+";
	$w .= "($d <> 'NULL') OR";
}
	$h = substr($h, 0,-4);

$filterLoss = ' OR (CAST(matmgdb.LOSS_QTY AS nvarchar) <> \'NULL\')';
$filterDate = ' (matmgdb.SAVED_DATE BETWEEN \''.$datestart.'\' AND \''.$dateend.'\')';
$filterPlant = " (matmgdb.PLANT = '$plant')";
$h = $h.$filterPlant;           

$datesql = substr($dd, 0,-1);
$datesql2 = substr($d3, 0,-1);
$w = substr($w, 0,-2);

$total = substr($total, 0,-1);
$totals .= "REPLACE(CAST($total AS numeric(18,1)),'.00','') AS Total";

// $use = "CONVERT(nvarchar,CAST(($total) - CASE WHEN ISNULL(dbo.matmgdb.LOSS_QTY, 0) <= 0 THEN 0 END AS numeric(18,1))) AS Used";
$use = "CONVERT(nvarchar,$total - ISNULL(dbo.matmgdb.LOSS_QTY,0)) AS Used";

$useperday = "CONVERT(nvarchar,CAST(($total - CASE WHEN ISNULL(dbo.matmgdb.LOSS_QTY, 0) <= 0 THEN 0 END )  / 7 AS numeric(18,1)))  AS UsePerDay";
$cost = "CONVERT(nvarchar,CONVERT(numeric(18,1),(($total) - CASE WHEN ISNULL(dbo.matmgdb.LOSS_QTY, 0) <= 0 THEN 0 END)  * CONVERT(numeric(18,1),matmg_pur.UNIT_PRICE))) AS Cost";
$cost2 = "CONVERT(NVARCHAR,(CONVERT(numeric(18,1),matmg_pur.UNIT_PRICE * ($total)))) as cost";

$dateHeader = '<h3>'.'Date : '.'<strong>'.date('d-m-Y',strtotime($datestart)).'</strong>'.' to '.'<strong>'.date('d-m-Y',strtotime($dateend)).'</strong>'.'</h3>';
// echo "$dateHeader";

// echo '<a class="exportPDF">ExportPDF</a>';


$sql ="drop table temp_cal_waste;

SELECT [MAT_CODE] as MATERIAL,$datesql into temp_cal_waste
FROM 
(
	SELECT     SAVED_DATE AS D, SUM(LOSS_QTY) AS sumQTY, MAT_CODE
FROM         matmgdb
WHERE     (PLANT = '$plant')
GROUP BY MAT_CODE, SAVED_DATE
	) s
PIVOT
(
	MAX(sumQTY)
	FOR D IN ($datesql)
	) t
";
// echo "$sql"."</br>";
$results1 = $conn->query($sql);

$sqlCanViewCostPerUnit = "SELECT DISTINCT matmg_pur.MAT_CODE as Code, 
matmg_pur.MAT_DEPART as Dep, matmg_pur.MAT_T_DESC as Name, 
matmg_pur.UNIT_CODE as unit ,$datesql2,$totals, 
CAST(matmg_pur.UNIT_PRICE AS numeric(18,1)) as costPerUnit, 
$cost2, treason.REASON_DETAIL

FROM         matmgdb INNER JOIN
                      treason ON matmgdb.WASTE_REASON = treason.REASON_ID RIGHT OUTER JOIN
                      temp_cal_waste RIGHT OUTER JOIN
                      matmg_pur ON temp_cal_waste.MATERIAL = matmg_pur.MAT_CODE ON matmgdb.MAT_CODE = matmg_pur.MAT_CODE


WHERE $filterDate
GROUP BY matmg_pur.MAT_CODE,matmg_pur.MAT_DEPART,matmg_pur.MAT_T_DESC, 
matmgdb.BEGINING_QTY,matmgdb.LOSS_QTY,$datesql, 
matmgdb.SAVED_DATE, matmg_pur.UNIT_PRICE, matmg_pur.UNIT_CODE,matmgdb.PLANT, 
treason.REASON_DETAIL

HAVING $h

";

$sqlCanNotViewCostPerUnit = "SELECT DISTINCT matmg_pur.MAT_CODE as Code, 
matmg_pur.MAT_DEPART as Dep, matmg_pur.MAT_T_DESC as Name, 
matmg_pur.UNIT_CODE as unit ,$datesql2,$totals, 

$cost2, treason.REASON_DETAIL

FROM         matmgdb INNER JOIN
                      treason ON matmgdb.WASTE_REASON = treason.REASON_ID RIGHT OUTER JOIN
                      temp_cal_waste RIGHT OUTER JOIN
                      matmg_pur ON temp_cal_waste.MATERIAL = matmg_pur.MAT_CODE ON matmgdb.MAT_CODE = matmg_pur.MAT_CODE


WHERE $filterDate
GROUP BY matmg_pur.MAT_CODE,matmg_pur.MAT_DEPART,matmg_pur.MAT_T_DESC, 
matmgdb.BEGINING_QTY,matmgdb.LOSS_QTY,$datesql, 
matmgdb.SAVED_DATE, matmg_pur.UNIT_PRICE, matmg_pur.UNIT_CODE,matmgdb.PLANT, 
treason.REASON_DETAIL

HAVING $h

";
// echo $sql3;
// $results = $conn->query($sql3);
// $table = printtable($results);
// echo $table;

// check permission canViewCostPerUnit
$checkPermissionSQL = " SELECT TOP (1) imp_emp2.nUserID, T_Permission.canViewCostPerUnit
FROM imp_emp2 INNER JOIN
T_Permission ON imp_emp2.levelCode = T_Permission.levelCode
WHERE (imp_emp2.nUserID = '".$_COOKIE['empcode']."') ";
$checkPermission = $conn->query( $checkPermissionSQL, PDO::FETCH_COLUMN, 1 );  
$row = $checkPermission->fetch(PDO::FETCH_ASSOC);
// var_dump($row['canViewCostPerUnit']);
if ($row['canViewCostPerUnit']) {
	$results = $conn->query($sqlCanViewCostPerUnit);
	$results2 = $results;
	$results2 = $conn->query($sqlCanViewCostPerUnit);
	$results2->execute();
	$results2=$results2->fetchAll(PDO::FETCH_ASSOC);
}
else {
	$results = $conn->query($sqlCanNotViewCostPerUnit);
	$results2 = $results;
	$results2 = $conn->query($sqlCanNotViewCostPerUnit);
	$results2->execute();
	$results2=$results2->fetchAll(PDO::FETCH_ASSOC);
	// echo $sqlCanNotViewCostPerUnit;
}
require '../helperfunc.php';
$dx=array();
// $dx["debugSQL"] = $sql3;
$exportButton    = '<button id="export_btn">ExportToExcel</button>';
$dx["html"] = $dateHeader.$exportButton;
$dx["res"]    = $results2;
$dx["colName"] = getColName($results);

// echo $dx["colName"];
$json=json_encode($dx);
echo $json;
exit;

return;

?>