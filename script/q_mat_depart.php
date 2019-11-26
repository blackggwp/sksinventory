<?php
include 'conn.php';
// print_r($_GET);
$depart = $_GET['depart'];
$reasonWaste = $_GET['reasonWaste'];
$empcode = $_GET['empcode'];
$plant = $_GET['plant'];
$outletCode = $_GET['outletCode'];
$dateqty = $_GET['dateqty'];
$keyType = $_GET['keytype'];
$brandid = $_GET['brandid'];
$dateShowHeader = date('d-m-Y', strtotime($dateqty));
$dateValue = $dateqty;
$m = $dateqty;
$d = $dateqty;

if (($plant != '') && ($depart != '')) {
    $sql = " SELECT DISTINCT MAT_CODE, MAT_T_DESC, MAT_DEPART, UNIT_CODE, MAT_GROUP,BRAND_ID, FORMAT(LAST_UPDATE, 'dd-MM-yyyy ') AS LAST_UPDATE
	FROM material_pur 
    WHERE BRAND_ID = '".$brandid."' ";
  if ($depart != 'all') {
    $sql .= " AND ([material_pur].MAT_DEPART = '".$depart."') ";
  }
  $sql .= " ORDER BY  dbo.material_pur.MAT_CODE ";
//   echo $sql;
  $results = $conn->query($sql);
}

?>
<div class="submitbtn">
    <h3>ยืนยันการบันทึกข้อมูล</h3>
    <button type="submit" class="submitdata btn btn-success">บันทึก</button>

</div> <!-- Submit Btn-->

<form method="post" id="form_inputqty" class="form_data" name="form_data">

    <table class="example table table-striped table-hover">
        <thead>
            <tr>
                <th>MAT_DEPART</th>
                <th>DATE</th>
                <th>MAT_CODE</th>
                <th>Description</th>
				<th>Last_Update</th>
                <th>Unit</th>       
                <th>Enter Data</th>

            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($results as $res) {
                echo '<tr class="trqty"> 
                    <td>'.$res[MAT_DEPART].'</td>
                    <td>'.$dateqty.'</td>                                         
                    <td>'.$res[MAT_CODE].'</td>
                    <td>'.$res[MAT_T_DESC].'
					<td>'.$res[LAST_UPDATE].'
                        <input type="hidden" name="mattdesc[]" value="'.$res[MAT_T_DESC].'">
                        <input type="hidden" name="mat_code[]" value="'.$res[MAT_CODE].'">
                        <input type="hidden" name="mat_depart[]" value="'.$res[MAT_DEPART].'">
                        <input type="hidden" name="reason_waste" value="'.$reasonWaste.'">
                        <input type="hidden" name="outletCode" value="'.$outletCode.'">
                        <input type="hidden" name="mat_group[]" value="'.$res[MAT_GROUP].'">
                        <input type="hidden" name="brandid" value="'.$res[BRAND_ID].'">
                    </td>';
                echo '<td>'.$res[UNIT_CODE].'
                <input type="hidden" name="unitcode[]" value="'.$res[UNIT_CODE].'">
                <input type="hidden" name="unitprice[]" value="'.$res[UNIT_PRICE].'"></td>';
                    echo '<td class="tdqty"> <input class="inputqty form-control" type="text" 
                    name="mat_qty[]"
                     >
                    </td>';                          
                echo '</tr>';
            }
                
                $conn = null;
            ?>
        </tbody>
    </table>
</form>

<div class="dialog-confirmdatadepart" title="Save Data?" style="display:none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Are you sure?</p>
</div>
<div class="dialog-message" title="Download complete" style="display:none;">
  <p>
    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
    Save to DB Success.
</p>
<div class="dialog-leavepage" title="Are you leave this page?" style="display:none;">
  <p>
    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
    Are you sure?
</p>
</div>