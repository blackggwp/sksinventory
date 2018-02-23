<?php
include 'conn.php';

$d = $_GET;
$datestart = $d['datestart'];
// $datestart = str_replace('/', '-', $datestart);
// $datestart = date('d-m-Y',strtotime($datestart));
// $pastWeek = strtotime('-7 day',$datestart);
$pastWeek = date('Y-m-d',strtotime($datestart.'-7 day'));

$plant = $d['plant'];

$chkTue = date('D',strtotime($datestart));
if ($chkTue == 'Tue') {
	$tuesday = date('Y-m-d',strtotime($datestart));
}else{
	$tuesday = date('Y-m-d',strtotime('last tuesday', strtotime($datestart)));
}
$firstday = $tuesday;
for ($i=1; $i <=7 ; $i++) {
	$d = $tuesday;
	$day = date("d",strtotime($tuesday));
	$d1 = '['.$tuesday.']'.',';
	$dwithoutAS .= $d1;
	$dayLetter = date("D",strtotime($tuesday));
	$d = 'CONVERT(nvarchar,CAST(['.$tuesday.']'.' AS numeric(18,1)))'.$dayLetter.$day.',';
	$cols .= $d;

	$h .= '(['.$tuesday.'] <> \'NULL\') OR ';

	$total .= "CAST(ISNULL(dbo.txw.[$tuesday],0) AS float)+";

	$endday = $tuesday;

	$tuesday = date('Y-m-d',strtotime($tuesday.'+1 day'));
	
}

$h = substr($h, 0,-4);
$filterEnding = ' OR (CAST(matmgdb.ENDING_QTY AS nvarchar) <> \'NULL\')';
$filterDate = ' AND (matmgdb.SAVED_DATE BETWEEN \''.$firstday.'\' AND \''.$endday.'\')';
// $filterDate = " AND (t1.SAVED_DATE BETWEEN '$firstday' AND '$endday')";
$filterPlant = " AND (matmgdb.PLANT = '$plant')";
$filterBeg = " AND (matmgdb_1.SAVED_DATE = '$pastWeek')";
// $h = $h.$filterEnding.$filterPlant.$filterDate;
$h = $h.$filterEnding.$filterPlant.$filterDate;

$datesql = substr($dwithoutAS, 0,-1);
$datesql2 = substr($cols, 0,-1);

$total = substr($total, 0,-1);
$totals .= "REPLACE(CAST($total AS numeric(18,1)),'.00','') AS Total";

// $use = "CONVERT(nvarchar,CAST(($total) - CASE WHEN ISNULL(dbo.matmgdb.ENDING_QTY, 0) <= 0 THEN 0 END AS numeric(18,1))) AS Used";
$use = "CONVERT(nvarchar,$total - ISNULL(dbo.matmgdb.ENDING_QTY,0)) AS Used";

// $useperday = "CONVERT(nvarchar,CAST(($total - CASE WHEN ISNULL(dbo.matmgdb.ENDING_QTY, 0) <= 0 THEN 0 END )  / 7 AS numeric(18,1)))  AS UsePerDay";
$useperday = "CONVERT(nvarchar,ROUND((($total - ISNULL(dbo.matmgdb.ENDING_QTY,0))  / 7),1)) AS UsePerDay";

// $cost = "CONVERT(nvarchar,CONVERT(numeric(18,1),(($total) - CASE WHEN ISNULL(dbo.matmgdb.ENDING_QTY, 0) <= 0 THEN 0 END)  * CONVERT(numeric(18,1),matmg_pur.price))) AS Cost";
$cost = "CONVERT(numeric(18,1),($total - ISNULL(dbo.matmgdb.ENDING_QTY,0)) * matmg_pur.price) as Cost";

// $rptHeader = '<h3>Week Report</h3></br>';
$dateHeader =  '<h3>'.'Date : '.'<strong>'.date('d-m-Y',strtotime($firstday)).'</strong>'.' to '.'<strong>'.date('d-m-Y',strtotime($endday)).'</strong>'.'</h3>';
// echo $rptHeader;
// echo $dateHeader;
// echo '<form method ="POST" name="exportPDF" action="script/exportPDF.php">';
// $ajdata = $table;
// echo '<input type="submit" name="exportPDF" value="'.$ajdata.'">';

// $exportPDFLink = '<a class="exportPDF">ExportPDF</a>';
// echo "</form>";

// $sql ="drop table txw;

// SELECT [MATERIAL],$datesql into txw
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

// $results1 = $conn->query($sql);

$sql1 ="
		DROP table txw; SELECT  SUBSTRING([MATERIAL],9,10) as MATERIAL ,$datesql into txw
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
$tempTable = $conn->query($sql1);

$sql2 = "SELECT matmgdb_1.BEGINING_QTY AS Beg,t1.* FROM (SELECT DISTINCT matmg_pur.MAT_CODE as Code,matmg_inventory.MAT_DEPART as Dep, matmg_inventory.MAT_T_DESC as Name , matmg_inventory.UNIT_CODE as unit,matmgdb.SAVED_DATE,$datesql2,$totals, matmgdb.ENDING_QTY as Ending,$use,$useperday, CAST(matmg_pur.price AS numeric(18,1)) as costPerUnit,$cost

 FROM matmgdb RIGHT OUTER JOIN
 matmg_pur INNER JOIN
 matmg_inventory ON matmg_pur.MAT_CODE = matmg_inventory.MAT_CODE ON matmgdb.MAT_CODE = matmg_pur.MAT_CODE LEFT OUTER JOIN
 txw ON matmg_pur.MAT_CODE = txw.MATERIAL

GROUP BY matmg_pur.MAT_CODE,matmg_inventory.MAT_DEPART,matmg_inventory.MAT_T_DESC,matmgdb.BEGINING_QTY,matmgdb.ENDING_QTY,$datesql, matmgdb.SAVED_DATE, matmg_pur.price, matmg_inventory.UNIT_CODE,matmgdb.PLANT

HAVING $h) AS t1 LEFT OUTER JOIN
                      matmgdb AS matmgdb_1 ON matmgdb_1.MAT_CODE = t1.Code 
                       $filterBeg
";
// echo $sql2;

$results = $conn->query($sql2);
$results2 = $results;
$results2->execute();
$results2=$results2->fetchAll(PDO::FETCH_ASSOC);

$dx=array();
// $dx["debugQuery"]=$sql2;
$dx["html"] = $dateHeader.$exportPDFLink;
$dx["res"]    = $results2;
$dx["colName"] = getColName($results);

// echo $dx["colName"];
$json=json_encode($dx);
echo $json;
exit;

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

function pre($dr){
	echo '<pre>';
	print_r($dr);
	echo '</pre>';
}
function toJson($results){
	$jres = json_encode($results);
	echo $jres;
}
function printtable($results,$cost){

	$tcolumn = $results->columnCount();
	$h = '';
	$f = '';
	$cols=array();
	$sumCost = '';
		$h .= '<tr>';

	for ($counter = 0; $counter < $tcolumn; $counter ++) {
		$meta = $results->getColumnMeta($counter);
		$h.='<th style="font-size:1em;">'.$meta['name'].'</th>';
		
	}   
		$h .= '</tr>';
	$r='';

	foreach ($results as $dr) {

		if ($dr['Dep'] == 'KITCHEN') {
				$sumCost = $sumCost + $dr['Cost'];

		}
		// else{
		// 	$r.='<tr>';
		// 		for ($i=0; $i < $tcolumn; $i++) { 
					
		// 			$r.='<td>'.$sumCost.'</td>';
						
		// 		}
			
		// 	$r.='</tr>';
		// }
		$r.='<tr>';

		for ($i = 0; $i < $tcolumn; $i ++) {
			$val=str_replace(".00","",$dr[$i]);

			// $val=$dr[$i];
			// $val = str_replace(".00","",number_format($dr[$i], 2, '.', ''));

			$r.='<td>'.$val.'</td>';

			
		}

		

		$r.='</tr>';

		
		
	}
		
	$f ='<tfoot>'.$h.'</tfoot>';

	// $f = '<tfoot>
 //            <tr>
 //                <th colspan="18" style="text-align:right">Total:</th>
 //                <th></th>
 //            </tr>
 //        </tfoot>';
	$h ='<thead>'.$h.'</thead>';
	$s.='<table id="tblreport" class="tblreport table table-hover">'.$h.'<tbody>'.$r.'</tbody>'.$f;
	$s.='</table>';

	return $s;


}

return;

?>