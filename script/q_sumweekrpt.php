<?php
include 'conn.php';

$d = $_GET;
print_r($d);
$datestart = $d['datestart'];
$datestart = date('Y-m-d',strtotime($datestart));
$plant = $d['plant'];
$empcode = $d['empcode'];

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

$dateHeader =  'Date : '.date('d-m-Y',strtotime($firstday)).' to '.date('d-m-Y',strtotime($endday));

$desc = '<h4><strong>Branch: '.$plant.'</br>
		'.$dateHeader.'</br>
		Prepared By:  '.$empcode.'</br></strong></h4>';

echo "$desc";
?>