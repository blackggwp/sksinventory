<?php
include 'conn.php';
$d = $_GET;
$datestart = $d['datestart'];
// $dateend = $d['dateend'];
$datestart = date('Y-m-d',strtotime($datestart));
// $dateend = date('Y-m-d',strtotime($dateend));
$dateend = date('t',strtotime($datestart));
$dc = substr($datestart,0,8);
$dateend = date('Y-m-d',strtotime($dc.$dateend));
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
	$total .= "CAST(ISNULL(dbo.txm.[$i],0) AS float)+";
}
	$h = substr($h, 0,-4);
$filterEnding = ' (CAST(matmgdb.ENDING_QTY AS nvarchar) <> \'NULL\')';
$filterDate = ' AND (matmgdb.SAVED_DATE BETWEEN \''.$datestart.'\' AND \''.$dateend.'\')';
$filterPlant = " AND (matmgdb.PLANT = '$plant')";
$h = $h.$filterEnding.$filterPlant.$filterDate;       

$datesql = substr($dd, 0,-1);
$datesql2 = substr($d3, 0,-1);

$total = substr($total, 0,-1);
$totals .= "REPLACE(CAST($total AS numeric(18,1)),'.00','') AS Total";

// $use = "CONVERT(nvarchar,CAST(($total) - CASE WHEN ISNULL(dbo.matmgdb.ENDING_QTY, 0) <= 0 THEN 0 END AS numeric(18,1))) AS Used";
$use = "CONVERT(numeric(18,1),$total - ISNULL(dbo.matmgdb.ENDING_QTY,0)  ) AS Used";

// $useperday = "CONVERT(nvarchar,CAST(($total - CASE WHEN ISNULL(dbo.matmgdb.ENDING_QTY, 0) <= 0 THEN 0 END )  / 7 AS numeric(18,1)))  AS UsePerDay";
$useperday = "CONVERT(nvarchar,ROUND((($total - ISNULL(dbo.matmgdb.ENDING_QTY,0))  / 7),1)) AS UsePerDay";

// $cost = "CONVERT(nvarchar,CONVERT(numeric(18,1),(($total) - CASE WHEN ISNULL(dbo.matmgdb.ENDING_QTY, 0) <= 0 THEN 0 END)  * CONVERT(numeric(18,1),material_pur.UNIT_PRICE))) AS Cost";
$cost = "CONVERT(numeric(18,1),($total - ISNULL(dbo.matmgdb.ENDING_QTY,0))  * material_pur.UNIT_PRICE) AS Cost";


$dateHeader = '<h3>'.'Date : '.'<strong>'.date('d-m-Y',strtotime($datestart)).'</strong>'.' to '.'<strong>'.date('d-m-Y',strtotime($dateend)).'</strong>'.'</h3>';

// $exportPDFLink = '<a class="exportPDF">ExportPDF</a>';

$sql =" DROP table txm; SELECT  SUBSTRING([MATERIAL],9,10) as MATERIAL ,$datesql into txm
    FROM ( SELECT     CONVERT(datetime,tgrheader.[PSTNG_DATE],103) AS D, REPLACE(tgritems.[ENTRY_QNT],'.000','') AS QTY,  tgritems.MATERIAL
    FROM tgrheader LEFT OUTER JOIN
    tgritems ON tgrheader.MAT_DOC = tgritems.MAT_DOC
    WHERE (tgritems.MOVE_TYPE = '101')  AND (tgritems.PLANT = '$plant')
) s
 PIVOT (
 	MAX(QTY)
	FOR D IN ($datesql)
	) t
";
$tempTable = $conn->query($sql);

$sqlCanViewCostPerUnit = "SELECT DISTINCT material_pur.MAT_CODE as Code,material_pur.MAT_DEPART as Dep, material_pur.MAT_T_DESC as Name, material_pur.UNIT_CODE as unit , matmgdb.BEGINING_QTY as Beg ,$datesql2,$totals, matmgdb.ENDING_QTY as Ending,$cost,CAST(material_pur.UNIT_PRICE AS numeric(18,1)) as costPerUnit,$use,$useperday 
FROM txm RIGHT OUTER JOIN
material_pur ON txm.MATERIAL = material_pur.MAT_CODE LEFT OUTER JOIN
matmgdb ON material_pur.MAT_CODE = matmgdb.MAT_CODE

GROUP BY material_pur.MAT_CODE,material_pur.MAT_DEPART,material_pur.MAT_T_DESC,matmgdb.BEGINING_QTY,matmgdb.ENDING_QTY,$datesql, matmgdb.SAVED_DATE, material_pur.UNIT_PRICE, material_pur.UNIT_CODE,matmgdb.PLANT 
HAVING $h
";

$sqlCanNotViewCostPerUnit = "SELECT DISTINCT material_pur.MAT_CODE as Code,material_pur.MAT_DEPART as Dep, material_pur.MAT_T_DESC as Name, material_pur.UNIT_CODE as unit , matmgdb.BEGINING_QTY as Beg ,$datesql2,$totals, matmgdb.ENDING_QTY as Ending,$cost,$use,$useperday 
FROM txm RIGHT OUTER JOIN
material_pur ON txm.MATERIAL = material_pur.MAT_CODE LEFT OUTER JOIN
matmgdb ON material_pur.MAT_CODE = matmgdb.MAT_CODE

GROUP BY material_pur.MAT_CODE,material_pur.MAT_DEPART,material_pur.MAT_T_DESC,matmgdb.BEGINING_QTY,matmgdb.ENDING_QTY,$datesql, matmgdb.SAVED_DATE, material_pur.UNIT_PRICE, material_pur.UNIT_CODE,matmgdb.PLANT 
HAVING $h
";

// check permission canViewCostPerUnit
$checkPermissionSQL = " SELECT TOP (1) imp_emp2.nUserID, T_Permission.canViewCostPerUnit
FROM imp_emp2 INNER JOIN
T_Permission ON imp_emp2.levelCode = T_Permission.levelCode
WHERE (imp_emp2.nUserID = '".$_COOKIE['empcode']."') ";
$checkPermission = $conn->query( $checkPermissionSQL, PDO::FETCH_COLUMN, 1 );  
$row = $checkPermission->fetch(PDO::FETCH_ASSOC);
// var_dump($row['canViewCostPerUnit']);
	// echo $sqlCanViewCostPerUnit;
if ($row['canViewCostPerUnit']) {
	$results = $conn->query($sqlCanViewCostPerUnit);
	$results2 = $results;
	$results2 = $conn->query($sqlCanViewCostPerUnit);
	$results2->execute();
	$results2=$results2->fetchAll(PDO::FETCH_ASSOC);
	// echo $sqlCanViewCostPerUnit;
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
// $dx["debugSQL"] = $sql2;
$dx["html"] = $dateHeader.$exportPDFLink;
$dx["res"]    = $results2;
$dx["colName"] = getColName($results);

// echo $dx["colName"];
$json=json_encode($dx);
echo $json;
exit;

// $table = printtable($results);

// echo $table;
// function printtable($results){

// 	$tcolumn = $results->columnCount();

// 	$h='';
// 	$cols=array();
// 	for ($counter = 0; $counter < $tcolumn; $counter ++) {
// 		$meta = $results->getColumnMeta($counter);
// 		$h.='<th style="font-size:1em;">'.$meta['name'].'</th>';

// 	}   

// 	$r='';
// 	foreach ($results as $dr) {

// 		$r.='<tr>';
// 		for ($i = 0; $i < $tcolumn; $i ++) {
// 			$val=str_replace(".00","",$dr[$i]);

// 			$r.='<td>'.$val.'</td>';
// 		}
// 		$r.='</tr>';
// 	}

// 	$h='<thead>'.$h.'</thead>';
// 	$s.='<table class="tblreport table table-hover">'.$h.$r;
// 	$s.='</table>';

// 	return $s;

// }

return;

?>