<style>
table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;

}

th, td {
  text-align: left;
  padding: 1px;
}

tr:nth-child(even){background-color: #f2f2f2}
</style>
<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>kardex/excel/<?php echo $permisos->opc_id?>/<?php echo $fec2?>/<?php echo $fec2?>" onsubmit="return exportar_excel2()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
       	<input type="button" value="REPORTE" class="btn btn-warning"  onclick="validar()" style="float:right;"/>
   
      <h1>
        Kardex
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-8">
					<form action="<?php echo $buscar;?>" method="post" id="frm_buscar">
						
					<table width="100%">
						<tr>
							<td class="hidden-mobile" ><label>Buscar:</label></td>
							<td class="hidden-mobile"><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td hidden>
								<select name="tipo" id="tipo" class="form-control" style="width: 200px">
									<option value="26">PRODUCTO TERMINADO</option>
								</select>
								<script>
									var tp="<?php echo $ids?>";
									tipo.value=tp;
								</script>
							</td>
							<td ><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control" value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control"  value='<?php echo $fec2?>' /></td>
							<td><button type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
								</td>
						</tr>
					</table>
					</form>
				</div>		
				
			</div>
			<br>
			<div class="row" >
				<div class="col-md-12" style="overflow-x:auto;">
					
						<?php echo $detalle?>
						
				</div>	
			</div>
		</div>
	</div>
<script type="text/javascript">
	window.onload = function () {

      var mensaje ='<?php echo $mensaje;?>';
      if(mensaje != '')
      {

        swal("", mensaje, "info");
      }
      
    }

    function validar(){
			url="<?php echo base_url();?>kardex/show_frame/<?php echo $permisos->opc_id?>";
			$('#frm_buscar').attr('action',url);
			$('#frm_buscar').submit();
	}
</script>

</section>

