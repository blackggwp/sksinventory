<?php
include 'header.php';

  $outletName = 'Sukishi BBQ Central Pinklao ';
  // echo $outletName;
  $outletKeywords = array('bbq','buffet','seoul');
  foreach($outletKeywords as $outletKeyword){
    $check = stripos($outletName, $outletKeyword);
    var_dump($check);
    if ($check !== false) {
      $outletBrand = $outletKeyword;
    }
  }
  echo $outletBrand;

include 'footer.php';
?>