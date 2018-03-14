<?php
date_default_timezone_set('Asia/Bangkok');
?>
<div class="page-header">
  <h3>รายงานสูญเสีย</h3>
</div>
<form id="frmrpt">
<div class="container-fluid">
	<div class="showdaterpt">
		<p>จากวันที่
		<input type="text" name="datestart" class="datestart" readonly="readonly" value="<? echo date('d-m-Y');?>">
		ถึง<input type="text" name="dateend" class="dateend" readonly="readonly" value="<? echo date('d-m-Y');?>"></p>

		 <!-- <input  name="datestart" type="text" class="datestart" placeholder="From" readonly="readonly">
		 </br>
		 <input name="dateend" type="text" class="dateend" placeholder="To" readonly="readonly">
		 </br> -->

	</div>
	<br>
	<div class="showrpt">
		<button type="button" class="showrptbtn btn btn-success">แสดงรายงาน</button> 
	</div>
</div>
</form>
<div>
	<div class="result table-responsive">
	</div>
	<br>
		<div id="tblreport"></div> <!-- Datagrid  Devexpress-->
	
</div>