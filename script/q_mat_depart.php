<?php
include 'conn.php';
// print_r($_GET);
$depart = $_GET['depart'];
$group = $_GET['group'];
$empcode = $_GET['empcode'];
$plant = $_GET['plant'];
$dateqty = $_GET['dateqty'];
$keyType = $_GET['keytype'];
$dateShowHeader = date('d-m-Y', strtotime($dateqty));
$dateValue = $dateqty;
$m = $dateqty;
$d = $dateqty;

if (($plant != '') && ($depart != '')) {
    $sql = "SELECT DISTINCT matmg_inventory.MAT_CODE, matmg_inventory.MAT_T_DESC, matmg_inventory.MAT_DEPART, matmg_inventory.UNIT_CODE
FROM         matmg_inventory LEFT OUTER JOIN
                      MATERIAL_MASTER ON matmg_inventory.MAT_CODE = MATERIAL_MASTER.MAT_CODE
  WHERE 
  (dbo.MATERIAL_MASTER.PLANT  = '".$plant."') ";
  if ($depart != 'all') {
    $sql .= " AND ([matmg_inventory].MAT_DEPART = '".$depart."') ";
  }

  $sql .= " ORDER BY  dbo.matmg_inventory.MAT_CODE ";
  // echo "$sql";
  $results = $conn->query($sql);
}

?>
<div class="submitbtn">
    <h3>ยืนยันการบันทึกข้อมูล</h3>
    <button type="submit" class="submitdata btn btn-success">บันทึก</button>

</div> <!-- Submit Btn-->

<form method="post" class="form_data" name="form_data">

    <table class="example table table-striped table-hover">
        <thead>
            <tr>
                <th>MAT_DEPART</th>
                <th>MAT_CODE</th>
                <th>Description</th>
                <th>Unit</th>       
                <th>Enter Data</th>

            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($results as $res) {
                echo '<tr class="trqty"> 
                    <td>'.$res[MAT_DEPART].'</td>                                            
                    <td>'.$res[MAT_CODE].'</td>
                    <td>'.$res[MAT_T_DESC].'
                        <input type="hidden" name="mattdesc[]" value="'.$res[MAT_T_DESC].'">
                        <input type="hidden" name="mat_code[]" value="'.$res[MAT_CODE].'">
                        <input type="hidden" name="mat_depart[]" value="'.$depart.'">
                    </td>';
                echo '<td>'.$res[UNIT_CODE].'
                <input type="hidden" name="unitcode[]" value="'.$res[UNIT_CODE].'">
                <input type="hidden" name="unitprice[]" value="'.$res[UNIT_PRICE].'"></td>';
                    echo '<td class="tdqty"> <input class="inputqty form-control" type="text" min="1" 
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