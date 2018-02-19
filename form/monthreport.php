<div class="page-header">
  <h3>Month Report</h3>
</div>
<form id="frmrpt">
<div class="container-fluid">
	<div class="showdaterpt">
		<!-- <p>From<input type="text" name="datestart" class="datestart" readonly="readonly" value="<? echo date('d-m-Y');?>">
		To<input type="text" name="dateend" class="dateend" readonly="readonly" value="<? echo date('d-m-Y');?>"></p> -->
		<input type="text" name="datestart" class="datestart" readonly="readonly" placeholder="Choose month">
	</div>
	<div class="showrpt">
		<button type="button" class="showrptbtn btn btn-success">Show Report</button> 
	</div>
</div>
</form>
<div>
	<div class="result table-responsive">
	</div>
		<div id="tblreport"></div> <!-- Datagrid  Devexpress-->
	
</div>