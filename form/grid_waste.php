<?
include 'script/conn.php';
$g = $_GET;
$queryReason = ' SELECT REASON_ID, REASON_DETAIL FROM treason ';
$resReasons = $conn->query($queryReason);
?>
<html>
<head>
</head>
<body>
  <div class="container-fluid">
    <div class="row-fluid">
    <div class="txtKeyType">
    <h1>คีย์สูญเสีย</h1>
  </div>
      <div class="txtdate">
      <h2>1.เลือกวันที่สูญเสีย:</h2>
        <input type="text" name="dateqty" class="inputdateqty" value="" placeholder="เลือกวันที่">
      </div>
      <div id="secFilterDepart">  <!--******** Section Filter Department -->
        <form name="form_grid_waste">
          <h2>2.เลือกแผนก:</h2>
          <select class="dropdownDepart form-control input" name="selectFilterDepart" required></br>
            <option selected disabled>เลือกแผนก</option>
            <option value="all">All</option>
            <option value="kitchen">Kitchen</option>
            <option value="korean">Korean</option>
            <option value="service">Service</option>
            <option value="checker">Checker</option>
            <option value="sushi">Sushi</option>
            <option value="bar">Bar</option>
            <option value="wash">Wash</option>
          </select>
      </div>
      <div>
      <h2>3.เลือกเหตุผลการสูญเสีย:</h2>
      <?php

      ?>
          <select class="dropdownReason form-control input" name="selectReason" required></br>
            <option selected disabled>เลือกเหตุผล</option>
            <?php
              foreach ($resReasons as $resReason) {
              echo '<option value="'.$resReason[REASON_ID].'">'.$resReason[REASON_DETAIL].'</option>';
              }
            ?>
            <!-- <option value="other">อื่นๆ</option> -->
          </select>
      </div>
        <?php $conn = null; //Close DB connection ?>
        </form>
      </div>
    </div>
    <br>
    <div class="showresult">
    </div>
  </body>
  </html>