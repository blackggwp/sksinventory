<?
include 'script/conn.php';
$g = $_GET;

$sql = 'SELECT [MAT_GROUP],[MAT_GROUP_NAME] 
FROM [MATERIAL_GROUP]';

$sqlDepart = $conn->query($sql);

?>
<html>
<head>

</head>
<body>

  <div class="container-fluid">
    <div class="row-fluid">
    <div class="txtKeyType">
    <h1>คีย์คงเหลือ</h1>
  </div>
      <div class="txtdate">
      <h2>วันที่:</h2>
        <input type="text" name="dateqty" class="inputdateqty" value="" placeholder="เลือกวันที่">
      </div>
      <div id="secFilterDepart">  <!--******** Section Filter Department -->
        <form>
          <h2>แผนก:</h2>
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
          <?php    
            $conn = null; //Close the connection 
            ?>
          </form>
        </div>
      </div>
    </div>
    <br>
    <div class="showresult">
    </div>
  </body>
  </html>