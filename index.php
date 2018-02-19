<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- <link href="css/theme.css" rel="stylesheet"> -->
  <link href="css/bootstrap_yeti.css" rel="stylesheet">
  
  <link href="jqueryui/jquery-ui.min.css" rel="stylesheet">
  <link href="jqueryui/jquery-ui.theme.min.css" rel="stylesheet">

  

  
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <title>Inventory</title>
    </head>
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
    <script src="js/jquery-3.1.0.min.js"></script>
    <script src="jqueryui/jquery-ui.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>   <!-- External Data table search api-->
    <script type="text/javascript" src="js/fn.js"></script>
    <!-- <script type="text/javascript" src="js/jspdf.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>

<!-- devexpress -->
  <link rel="stylesheet" type="text/css" href="https://cdn3.devexpress.com/jslib/16.2.4/css/dx.common.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn3.devexpress.com/jslib/16.2.4/css/dx.light.css" />
    <!-- A DevExtreme library -->
    <script type="text/javascript" src="js/dx.all.js"></script> 

  <!-- <script type="text/javascript" src="https://cdn3.devexpress.com/jslib/16.2.4/js/dx.all.js"></script> -->

    <!-- Export btn include -->
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<!-- Export btn include -->
  </body>
  </html>