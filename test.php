<!DOCTYPE html>
<html lang="en">
<?php
include 'header.php';
include 'script/conn.php';
  $sql = " SELECT Outletcode, plantid, Outletname
  FROM         matmg_outlet
  WHERE     (Outletname NOT LIKE '%ปิด%') AND (plantid IS NOT NULL) ";
  $outletResults = $conn->query($sql);
  $copy = $outletResults;
//   foreach($outletResults as $outlet){
    //   echo '<hr>';
    // print_r($outlet[Outletname]);
    // echo '</hr>';
    $t = [];
    $t[0] = 1;
    $t[1] = 2;
    $t[2] = 3;
    echo $ck['plant'];
    if ($ck['plant'] != '') {

    }
    
    echo '<select class="dropDownOutletCode form-control input" name="selectPlant" style="width: 100%;height: 100%;" required>
                    <option selected disabled>เลือกสาขาที่นี่</option>';
                        foreach ($outletResults as $outlet2) {
                          echo '<option value="'.$outlet2[plantid].'">สาขา: '.$outlet2[Outletname].'   Plant: '.$outlet2[plantid].'</option>';
                        }
                echo  '</select>';
//   }
//   $outletCode = "บางกะปิ (ชั่วครปิาว)";
//   echo $outletCode;
//   if(strpos($outletCode,'ปิด') == false) {
    //   echo 'not found';
//   }
?>
<body>
    


</body>
<footer>
<?php
include 'footer.php';
?>
</footer>
</html>