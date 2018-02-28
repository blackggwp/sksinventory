<?php
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