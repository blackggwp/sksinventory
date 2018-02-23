<?php
include 'conn.php';
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
	
	$h .= '(sum(cast(['.$i.'] as float)) > 0) OR ';
	$total .= "CAST(ISNULL(dbo.txwes.[$i],0) AS float)+";
	$w .= "($d <> 'NULL') OR";
}
	$h = substr($h, 0,-4);

$filterLoss = ' OR (CAST(matmgdb.LOSS_QTY AS nvarchar) <> \'NULL\')';
$filterDate = ' (matmgdb.SAVED_DATE BETWEEN \''.$datestart.'\' AND \''.$dateend.'\')';
$filterPlant = " AND (matmgdb.PLANT = '$plant')";
$h = $h.$filterPlant;           

$datesql = substr($dd, 0,-1);
$datesql2 = substr($d3, 0,-1);
$w = substr($w, 0,-2);

$total = substr($total, 0,-1);
$totals .= "REPLACE(CAST($total AS numeric(18,1)),'.00','') AS Total";

// $use = "CONVERT(nvarchar,CAST(($total) - CASE WHEN ISNULL(dbo.matmgdb.LOSS_QTY, 0) <= 0 THEN 0 END AS numeric(18,1))) AS Used";
$use = "CONVERT(nvarchar,$total - ISNULL(dbo.matmgdb.LOSS_QTY,0)) AS Used";

$useperday = "CONVERT(nvarchar,CAST(($total - CASE WHEN ISNULL(dbo.matmgdb.LOSS_QTY, 0) <= 0 THEN 0 END )  / 7 AS numeric(18,1)))  AS UsePerDay";
$cost = "CONVERT(nvarchar,CONVERT(numeric(18,1),(($total) - CASE WHEN ISNULL(dbo.matmgdb.LOSS_QTY, 0) <= 0 THEN 0 END)  * CONVERT(numeric(18,1),matmg_pur.price))) AS Cost";
$cost2 = "CONVERT(NVARCHAR,(CONVERT(numeric(18,1),matmg_pur.PRICE * ($total)))) as cost";

$dateHeader = '<h3>'.'Date : '.'<strong>'.date('d-m-Y',strtotime($datestart)).'</strong>'.' to '.'<strong>'.date('d-m-Y',strtotime($dateend)).'</strong>'.'</h3>';
echo "$dateHeader";

// echo '<a class="exportPDF">ExportPDF</a>';


$sql ="drop table txwes;

SELECT [MAT_CODE] as MATERIAL,$datesql into txwes
FROM 
(
	SELECT [SAVED_DATE] as D, REPLACE([LOSS_QTY],'.0',' ') as QTY,[MAT_CODE]
	FROM matmgdb
	WHERE (dbo.matmgdb.Plant = '$plant')
	) s
PIVOT
(
	MAX(QTY)
	FOR D IN ($datesql)
	) t
";
// $sql2 ="
// 		DROP table txm; SELECT  SUBSTRING([MATERIAL],9,10) as MATERIAL ,$datesql into txm
//                                     FROM (SELECT     CONVERT(datetime,tgrheader.[PSTNG_DATE],103) AS D, REPLACE(tgritems.[ENTRY_QNT],'.000','') AS QTY,  tgritems.MATERIAL
//                                                             FROM          tgrheader LEFT OUTER JOIN
//                       tgritems ON tgrheader.MAT_DOC = tgritems.MAT_DOC
//                                                             WHERE (tgritems.MOVE_TYPE = '101')  AND (tgritems.PLANT = '$plant')
// ) s
//  PIVOT (
//  	MAX(QTY)
// 	FOR D IN ($datesql)
// 	) t
// ";

// echo "$sql"."</br>";
$results1 = $conn->query($sql);

$sql3 = "SELECT DISTINCT matmg_pur.MAT_CODE as Code,matmg_inventory.MAT_DEPART as Dep, matmg_inventory.MAT_T_DESC as Name, matmg_inventory.UNIT_CODE as unit ,$datesql2,$totals, CAST(matmg_pur.price AS numeric(18,1)) as costPerUnit, $cost2

FROM matmgdb RIGHT OUTER JOIN
    txwes RIGHT OUTER JOIN
    matmg_pur INNER JOIN
    matmg_inventory ON matmg_pur.MAT_CODE = matmg_inventory.MAT_CODE ON txwes.MATERIAL = matmg_pur.MAT_CODE ON 
    matmgdb.MAT_CODE = matmg_pur.MAT_CODE

WHERE $filterDate
GROUP BY matmg_pur.MAT_CODE,matmg_inventory.MAT_DEPART,matmg_inventory.MAT_T_DESC,matmgdb.BEGINING_QTY,matmgdb.LOSS_QTY,$datesql, matmgdb.SAVED_DATE, matmg_pur.price, matmg_inventory.UNIT_CODE,matmgdb.PLANT

HAVING $h

";
// echo "$sql3";
$results = $conn->query($sql3);
$table = printtable($results);
echo $table;

function printtable($results){

	$tcolumn = $results->columnCount();

	$h='';
	$cols=array();
	for ($counter = 0; $counter < $tcolumn; $counter ++) {
		$meta = $results->getColumnMeta($counter);
		// $utf8 = iconv("tis-620", "utf-8", $meta['name'] );
		$h.='<th style="font-size:1em;">'.$meta['name'].'</th>';

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

return;

?>