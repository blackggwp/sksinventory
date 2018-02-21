<?php
  include './script/conn.php';
  $sql = " SELECT     Outletcode, plantid, Outletname
  FROM         matmg_outlet
  WHERE     (plantid IS NOT NULL) AND (Outletname NOT LIKE '%ปิด%') ";
  $outletResults = $conn->query($sql);
  $ck = $_COOKIE;
  // get outletCode
  if ($ck['plant'] != '') {
    foreach($outletResults as $outlet1){
      if ($outlet1[plantid] == $ck['plant']) {
        $outletCode = $outlet1[Outletcode];
        break;
      }
    }
  }
  
?>
<img src="./img/loading/ring.gif" id="loading-indicator" style="display:none" />

<div id="modalLogin">
    <form id="form_login" name="form_login">
        <!-- <a class="btn btn-primary" data-toggle="modal" href='#modal-id'>Trigger modal</a> -->
   		<span class="login-signup">
		<h4><strong>รหัสพนักงาน: <?echo $ck['empcode'];?>
   		สาขา: <?echo $outletCode;?>
        <a class="logoutbtn btn btn-warning">ออกจากระบบ</a></strong></h4>
 		</span>

        <div class="modalLogin modal fade" id="modal-id" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <!-- <button type="button" class="close" >&times;</button> -->
                <h4 class="modal-title">กรุณา เข้าสู่ระบบ</h4>
              </div>
              <div class="modal-body">
                <h2>สาขา</h2>
                  <label id="selectPlant-error" class="error" for="selectPlant"></label><br>
                  <select class="dropDownOutletCode form-control input" name="selectPlant" style="width: 100%;height: 100%;" required>
                    <option selected disabled>เลือกสาขาที่นี่</option>
                      <?php
                        foreach ($outletResults as $outlet2) {
                          echo '<option value="'.$outlet2[plantid].'">สาขา: '.$outlet2[Outletcode].'   Plant: '.$outlet2[plantid].'</option>';
                        }
                      ?>
                  </select>
                <h2>รหัสพนักงาน</h2>
                <label id="empcode-error" class="error" for="empcode"></label><br>
                <input class="empcode form-control" type="number" name="empcode" placeholder="ระบุรหัสพนักงาน">
              </div>
              <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                <button type="submit" id="submitlogin" class="btn btn-primary">เข้าสู่ระบบ</button>
              </div>
            </div>
          </div>
        </div>
          
        </form>
      </div>