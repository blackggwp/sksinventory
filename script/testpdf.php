<?
require_once(dirname(__FILE__).'/mpdf/mpdf.php');
exportPDF('<p>sdsdsd</p>');
function exportPDF($table){
$date = date('d_m_Y');
$filepdf = (dirname(__FILE__).'/../mat'.$date.'.pdf');

$mpdf = new mPDF('th', 'A4', '0', '');   //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
$mpdf->SetDisplayMode('fullpage');
 
$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
$mpdf->WriteHTML($table);
         
// $mpdf->Output(dirname(__FILE__).'/../matmg.pdf');
// $mpdf->Output($filepdf, 'F');

// window.open('$filepdf');
$mpdf->Output('MyPDF.pdf','D');
// $filename = "MyPDF.pdf";
// if (file_exists($filename)) {
//    header('Content-type: application/force-download');
//    header('Content-Disposition: attachment; filename='.$filename);
//    readfile($filename);
// }
}
?>