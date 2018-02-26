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
?>