<form id="frmrpt">
<div class="container-fluid">
	<div class="showdaterpt">
		<p>From<input type="date" name="datestart" class="datestart" value="<? echo date('Y-m-d');?>">
		To<input type="date" name="dateend" class="dateend" value="<? echo date('Y-m-d');?>"></p>
	</div>
	<div class="showrpt">
		<button type="button" class="showrptbtn btn btn-success">Show Report</button> 
		<!-- <a class="exportbtn btn btn-warning">export</a> -->
	</div>
</div>
</form>
<div>
	<div class="result table-responsive">
		
	</div>
</div>