<?php
if (isset($_GET['q'])) {
    include 'conn.php';
    $q = $_GET['q'];

    $sql = "SELECT DISTINCT(MATERIAL.MAT_CODE), MATERIAL.MAT_T_DESC, matmg.MAT_QTY
    FROM MATERIAL LEFT OUTER JOIN
    matmg ON MATERIAL.MAT_CODE = matmg.MAT_CODE
    WHERE MATERIAL.MAT_GROUP = '".$q."'";
    $results = $conn->query($sql);      
    ?>
    <div class="submitbtn">
        <h3>Confirm Data</h3>
        <button type="button" class="submitdata btn btn-success">Submit</button>
        <button type="button" class="sbtn">Submit</button>
    </div> <!-- Submit Btn-->
    <form method="post" class="form_data" name="form_data">

        <?php  
        if (isset($_GET['q'])) {

            ?>
            <table class="example table table-striped table-hover">
                <thead>
                    <tr>
                        <th>MAT_CODE</th>
                        <th>Description</th>
                        <th>Enter Data</th>
                        <th>Loss Data</th>
                    </tr>
                </thead>
                <tbody>

                    <?php   
                    foreach ($results as $res)  {
                        echo '<tr class="enterqty">                                             
                        <td>'.$res[MAT_CODE].'</td>
                        <td>'.$res[MAT_T_DESC].'</td>                       
                        <td class="tdqty"> <input class="fieldqty form-control" type="number" min="1" name="mat_qty[]" maxlength="2" value="'.$res[MAT_QTY].'">
                            <input type="hidden" name="mat_code[]" value="'.$res[MAT_CODE].'">
                            <input type="hidden" name="mat_group[]" value="'.$q.'">
                        </td>
                        <td class="tdqty"> <input class="fieldlossqty form-control" type="number" min="1" name="loss_qty[]" maxlength="2" value="'.$res[LOSS_QTY].'"></td>                
                    </tr>';

                }

                $conn = null;
            }

    }  //end if
    ?>
</tbody>
</table>
</form>

<div class="dialog-confirmdatatype" title="Save Data?" style="display:none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Are you sure?</p>
</div>
<div class="dialog-confirmdatatype" title="Download complete" style="display:none;">
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