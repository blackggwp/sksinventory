<?php
  include './script/conn.php';
  $sql = "SELECT [PLANT]
          FROM [SKS_WEB].[dbo].[PLANT_MASTER]";
  $result = $conn->query($sql);
  $ck = $_COOKIE;
?>
<img src="./img/loading/ring.gif" id="loading-indicator" style="display:none" />

<div id="modalLogin">
    <form class="loginfrm">
        <!-- <a class="btn btn-primary" data-toggle="modal" href='#modal-id'>Trigger modal</a> -->
   		<span class="login-signup">
		<h4><strong>Empcode: <?echo $ck['empcode'];?>
   		plant: <?echo $ck['plant'];?>
        <a class="logoutbtn btn btn-warning">Logout</a></strong></h4>
 		</span>

        <div class="modalLogin modal fade" id="modal-id" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <!-- <button type="button" class="close" >&times;</button> -->
                <h4 class="modal-title">Please Login</h4>
              </div>
              <div class="modal-body">
                <h2>Plant</h2>
                      <select class="dropDownPlant form-control input" name="selectPlant" required></br>
                      <option selected disabled>Choose here</option>
                      <?
                        foreach ($result as $res) {
                          echo '<option value="'.$res[PLANT].'">'.$res[PLANT].'</option>';
                        }
                      ?>
                    </select>
                <h2>EmpCode</h2><input class="empcode form-control" type="number" min="1" name="empcode" maxlength="6">
              </div>
              <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                <button type="button" class="submitlogin btn btn-primary">Submit</button>
              </div>
            </div>
          </div>
        </div>
          
        </form>
      </div>