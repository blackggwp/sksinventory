<form id="frmrpt">
<div class="container-fluid">
	<div class="showdaterpt">
		<p>จากวันที่<input type="date" name="datestart" class="datestart" value="<? echo date('Y-m-d');?>">
		ถึง<input type="date" name="dateend" class="dateend" value="<? echo date('Y-m-d');?>"></p>
	</div>
	<div class="showrpt">
		<button type="button" class="showrptbtn btn btn-success">แสดงรายงาน</button> 
		<!-- <a class="exportbtn btn btn-warning">export</a> -->
	</div>
</div>
</form>
<div>
	<div class="result table-responsive">
		
	</div>
</div>