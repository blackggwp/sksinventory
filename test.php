<!DOCTYPE html>
<html lang="en">
<?php
include 'header.php';
include 'script/conn.php';
  $sql = " SELECT Outletcode, plantid FROM matmg_outlet ";
  $outletResults = $conn->query($sql);
//   print_r($outletResults);
  foreach($outletResults as $outlet){
    //   echo '<hr>';
    // print_r($outlet);
    // echo '</hr>';
    if ($outlet[plantid] == '10AG') {
        $outletCode = $outlet[Outletcode];
    }
    
  }
  echo $outletCode;
?>
<body>
    


</body>
<footer>
<?php
include 'footer.php';
?>
</footer>
</html>