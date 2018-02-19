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
	
	$h .= '(sum(cast(['.$i.'] as float)) > 0) OR ';
	$total .= "CAST(ISNULL(dbo.txm.[$i],0) AS float)+";
}
	$h = substr($h, 0,-4);
$filterEnding = ' OR (CAST(matmgdb.ENDING_QTY AS nvarchar) <> \'NULL\')';
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

// $cost = "CONVERT(nvarchar,CONVERT(numeric(18,1),(($total) - CASE WHEN ISNULL(dbo.matmgdb.ENDING_QTY, 0) <= 0 THEN 0 END)  * CONVERT(numeric(18,1),matmg_pur.price))) AS Cost";
$cost = "CONVERT(numeric(18,1),($total - ISNULL(dbo.matmgdb.ENDING_QTY,0))  * matmg_pur.price) AS Cost";


$dateHeader = '<h3>'.'Date : '.'<strong>'.date('d-m-Y',strtotime($datestart)).'</strong>'.' to '.'<strong>'.date('d-m-Y',strtotime($dateend)).'</strong>'.'</h3>';

// $exportPDFLink = '<a class="exportPDF">ExportPDF</a>';

// $sql ="drop table txm;

// SELECT [MATERIAL],$datesql into txm
// FROM
// (
// 	SELECT [Posting Date] as D, REPLACE([Qty in Un# of Entry],'.0',' ') as QTY,[MATERIAL]
// 	FROM tstolog
// 	WHERE [Movement Type] = '101'
// 	AND (dbo.tstolog.Plant = '$plant')
// 	) s
// PIVOT
// (
// 	MAX(QTY)
// 	FOR D IN ($datesql)
// 	) t
// ";

$sql ="
		DROP table txm; SELECT  SUBSTRING([MATERIAL],9,10) as MATERIAL ,$datesql into txm
                                    FROM (SELECT     CONVERT(datetime,tgrheader.[PSTNG_DATE],103) AS D, REPLACE(tgritems.[ENTRY_QNT],'.000','') AS QTY,  tgritems.MATERIAL
                                                            FROM          tgrheader LEFT OUTER JOIN
                      tgritems ON tgrheader.MAT_DOC = tgritems.MAT_DOC
                                                            WHERE (tgritems.MOVE_TYPE = '101')  AND (tgritems.PLANT = '$plant')
) s
 PIVOT (
 	MAX(QTY)
	FOR D IN ($datesql)
	) t
";
$tempTable = $conn->query($sql);

$sql2 = "SELECT DISTINCT MATERIAL_MASTER.MAT_CODE as Code,matmg_inventory.MAT_DEPART as Dep, matmg_inventory.MAT_T_DESC as Name, matmg_inventory.UNIT_CODE as unit , matmgdb.BEGINING_QTY as Beg ,$datesql2,$totals, matmgdb.ENDING_QTY as Ending,$use,$useperday, CAST(matmg_pur.price AS numeric(18,1)) as costPerUnit,$cost

FROM         matmg_inventory INNER JOIN
MATERIAL_MASTER ON matmg_inventory.MAT_CODE = MATERIAL_MASTER.MAT_CODE LEFT OUTER JOIN
matmg_pur ON MATERIAL_MASTER.MAT_CODE = matmg_pur.MAT_CODE LEFT OUTER JOIN
txm ON MATERIAL_MASTER.MAT_CODE = txm.MATERIAL LEFT OUTER JOIN
matmgdb ON MATERIAL_MASTER.MAT_CODE = matmgdb.MAT_CODE
WHERE     (MATERIAL_MASTER.PLANT = '$plant') 
GROUP BY MATERIAL_MASTER.MAT_CODE,matmg_inventory.MAT_DEPART,matmg_inventory.MAT_T_DESC,matmgdb.BEGINING_QTY,matmgdb.ENDING_QTY,$datesql, matmgdb.SAVED_DATE, matmg_pur.price, matmg_inventory.UNIT_CODE,matmgdb.PLANT

HAVING $h

";
$results = $conn->query($sql2);
$results2 = $results;
$results2 = $conn->query($sql2);
$results2->execute();
$results2=$results2->fetchAll(PDO::FETCH_ASSOC);



$dx=array();

$dx["html"] = $dateHeader.$exportPDFLink;
$dx["res"]    = $results2;
$dx["colName"] = getColName($results);

// echo $dx["colName"];
$json=json_encode($dx);
echo $json;
exit;

// $table = printtable($results);

// echo $table;
function getColName($results){
	$a=array();
	$tcolumn = $results->columnCount();
	for ($counter = 0; $counter < $tcolumn; $counter ++) {
		$meta = $results->getColumnMeta($counter);
		$colName .= '"'.$meta['name'].'"'.',';
		if ($meta['name'] == 'Dep') { //Chk Dep For group
			$a[]=array('dataField'=>$meta['name'],'groupIndex'=>0);
		}
		else{
		   $a[]=$meta['name'];
		}
	}
	return $a;
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

return;

?>