<?
include 'script/conn.php';
$g = $_GET;

$sql = 'SELECT [MAT_GROUP],[MAT_GROUP_NAME] 
FROM [SKS_WEB].[dbo].[MATERIAL_GROUP]';

$sqlDepart = $conn->query($sql);

?>
<html>
<head>

</head>
<body>

  <div class="container-fluid">
    <div class="row-fluid">
    <div class="txtKeyType">
    <h1>Key <? echo ''.$g['keytype'].'';?></h1>
  </div>
      <div class="txtdate">
        <input type="text" name="dateqty" class="inputdateqty" value="" placeholder="Choose Date">
      </div>
      <div id="secFilterDepart">  <!--******** Section Filter Department -->
        <form>
          <h2>Department: </h2>
          <select class="dropdownDepart form-control input" name="selectFilterDepart" required></br>
            <option selected disabled>Choose here</option>
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