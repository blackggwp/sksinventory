<!DOCTYPE html>
<html lang="en">
<?php
include 'header.php';
?>
    <body>

      <div class="container-fluid">
       <div id="header">
        <div class="page-header">
        <div id="login">
          <? include 'form/login.php';?>
        </div>
        
          <h1>Inventory</h1>

        </div>
        
      </div>
     <div id="nav">
        <? include 'form/nav.php';?>
      </div> 
      <!--<div id="side">
        <? //include 'form/side.php'; ?>
      </div> -->
      <div id="main">
        <div id="content">
          <? include 'form/grid.php';?>
          
        </div>
      </div>
      <div id="footer"></div>
    </div>
<?php
include 'footer.php';
?>
  </body>
  </html>