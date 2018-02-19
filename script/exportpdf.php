<?php
header('Content-Type: application/pdf'); 
header('Content-Description: inline; filename.pdf');
ini_set('memory_limit', '128M');
$libmpdf = dirname(__FILE__).'/mpdf/mpdf.php';

require_once($libmpdf);
$table = $_POST['html'];
echo $table;
exportPDF($table);
	



function pre($dr){
	echo '<pre>';
	print_r($dr);
	echo '</pre>';
}
function exportPDF($table){
$date = date('d_m_Y');
$filepdf = (dirname(__FILE__).'/../export/inven.pdf');

// $ck = unlink($filepdf);
// if ($ck) {
// 	echo "deleted true";
// }else{
// 	echo "del false";
// }

$mpdf = new mPDF('th', 'A4', '0', '');   //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
$mpdf->SetDisplayMode('fullpage');
 
$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
$stylesheet = file_get_contents('../css/theme.css');
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($table);
$mpdf->Output($filepdf);
// unlink('$filename');
$mpdf->open($filepdf);

echo "Export PDF Success";
}

?>
