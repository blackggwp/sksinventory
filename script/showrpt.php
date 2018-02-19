<?php
include 'conn.php';
$d = $_GET;
$datestart = $d['datestart'];
$dateend = $d['dateend'];
$plant = $d['plant'];

// 	$sql = "SELECT     dbo.tstolog.Material, dbo.tstolog.[Material Description] as MT, dbo.tstolog.Plant, dbo.tstolog.[Posting Date] as D, dbo.tstolog.[Qty in Un# of Entry] as QTY, dbo.tstolog.[Unit of Entry] as UN, 
//                       dbo.matmg_inventory.MAT_DEPART, dbo.tstolog.[Movement Type]
// FROM         dbo.tstolog LEFT OUTER JOIN
//                       dbo.matmg_inventory ON dbo.tstolog.Material = dbo.matmg_inventory.MAT_CODE
// 			WHERE (dbo.tstolog.[Movement Type] = '101')
// 			AND (dbo.tstolog.Plant = '".$plant."')
// 			";
// 	$sql1 = $sql .= "AND (dbo.tstolog.[Posting Date] BETWEEN '".$datestart."' AND '".$dateend."')";


function findFirstSunday($ds,$de) {

	for ($ds; $ds <= $de ; $ds++) {
		$checkDay = date('D',strtotime($ds));
		
		if ($checkDay == 'Sun') {
			return $ds;
		}elseif (($checkDay != 'Sun') &&($ds == $de)){
			$dayofweek = date('w', strtotime($ds));
			$sunday    = date('Y-m-d', strtotime((0 - $dayofweek).' day', strtotime($ds)));
			return $sunday;
		} 
	}
}

$firstSunday = findFirstSunday($datestart,$dateend);

for ($i=$datestart; $i <=$dateend  ; $i++) {

	$d = $i;
	$d = '['.$i.'],';
	$dd .= $d; 

	$d2 = $i;
	$d2 = substr($d2, 8); 

	$d = substr($d, 0,-1);
	$d3 .= $d.' AS '.'D'.$d2.',';
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						
	$h .= '(sum(cast(['.$i.'] as float)) > 0) OR ';
	
}
	$h = substr($h, 0,-4);
	$endandloss = ' OR (CAST(matmgdb.ENDING_QTY AS nvarchar) <> \'NULL\')';
    $h = $h.$endandloss;                  
$datesql = substr($dd, 0,-1);
$datesql2 = substr($d3, 0,-1);

$sql ="drop table tx;

SELECT [MATERIAL],$datesql into tx
FROM
(
	SELECT [Posting Date] as D, REPLACE([Qty in Un# of Entry],'.0',' ') as QTY,[MATERIAL]
	FROM tstolog
	WHERE [Movement Type] = '101'
	AND (dbo.tstolog.Plant = '$plant')
	) s
PIVOT
(
	MAX(QTY)
	FOR D IN ($datesql)
	) t
";
$results1 = $conn->query($sql);

$sql2 = "SELECT DISTINCT MATERIAL_MASTER.MAT_CODE as Code,matmg_inventory.MAT_DEPART as Dep, matmg_inventory.MAT_T_DESC as Name , matmgdb.BEGINING_QTY as Beg ,$datesql2, matmgdb.ENDING_QTY as Ending,
                      CONVERT(nvarchar, matmgdb.SAVED_DATE, 103) AS SaveDate

                     
FROM         matmg_inventory INNER JOIN
                      MATERIAL_MASTER ON matmg_inventory.MAT_CODE = MATERIAL_MASTER.MAT_CODE LEFT OUTER JOIN
                      tx ON MATERIAL_MASTER.MAT_CODE = tx.MATERIAL LEFT OUTER JOIN
                      matmgdb ON MATERIAL_MASTER.MAT_CODE = matmgdb.MAT_CODE
WHERE     (MATERIAL_MASTER.PLANT = '$plant')
GROUP BY MATERIAL_MASTER.MAT_CODE,matmg_inventory.MAT_DEPART,matmg_inventory.MAT_T_DESC,matmgdb.BEGINING_QTY,matmgdb.ENDING_QTY,$datesql, matmgdb.SAVED_DATE
                      
HAVING $h

";
// echo "$sql2";
$results = $conn->query($sql2);

$table = printtable($results);

echo $table;

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
			$r.='<td>'.$dr[$i].'</td>';
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