<?php
  include './script/conn.php';
  $sql = " SELECT Outletcode, plantid FROM matmg_outlet ";
  $outletResults = $conn->query($sql);
  $ck = $_COOKIE;
  // get outletCode
  foreach($outletResults as $outlet1){
    if ($outlet1[plantid] == $ck['plant']) {
      $outletCode = $outlet1[Outletcode];
      break;
    }
  }
?>
<img src="./img/loading/ring.gif" id="loading-indicator" style="display:none" />

<div id="modalLogin">
    <form id="form_login" name="form_login">
        <!-- <a class="btn btn-primary" data-toggle="modal" href='#modal-id'>Trigger modal</a> -->
   		<span class="login-signup">
		<h4><strong>รหัส: <?echo $ck['empcode'];?>
   		สาขา: <?echo $outletCode;?>
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
                  <label id="selectPlant-error" class="error" for="selectPlant"></label><br>
                  <select class="dropDownOutletCode form-control input" name="selectPlant" style="width: 100%;height: 100%;" required></br>
                    <option selected disabled>Choose here</option>
                      <?php
                        foreach ($outletResults as $outlet) {
                          echo '<option value="'.$outlet[plantid].'">สาขา: '.$outlet[Outletcode].'   Plant: '.$outlet[plantid].'</option>';
                        }
                      ?>
                  </select>
                <h2>EmpCode</h2>
                <label id="empcode-error" class="error" for="empcode"></label><br>
                <input class="empcode form-control" type="number" name="empcode">
              </div>
              <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                <button type="submit" id="submitlogin" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </div>
        </div>
          
        </form>
      </div>