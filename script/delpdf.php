<?php
$filepdf = (dirname(__FILE__).'/../export/inven.pdf');

// $ck = unlink('$filename');
$ck = array_map('unlink', glob("$filepdf"));
if ($ck) {
	echo "deleted file pdf complete";
}else{
	echo "deleted pdf false";
}

?>