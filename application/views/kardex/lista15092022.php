<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>kardex/excel/<?php echo $permisos->opc_id?>/<?php echo $fec2?>/<?php echo $fec2?>" onsubmit="return exportar_excel2()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Kardex <?php echo $titulo?>
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-8">
					<form action="<?php echo $buscar;?>" method="post">
						
					<table width="100%">
						<tr>
							<td><label>Buscar:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td>
								<select name="tipo" id="tipo" class="form-control" style="width: 200px">
									<option value="26">PRODUCTO TERMINADO</option>
									<!-- <option value="69">MATERIA PRIMA</option> -->
								</select>
								<script>
									var tp="<?php echo $ids?>";
									tipo.value=tp;
								</script>
							</td>
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control" style="width: 150px" value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control" style="width: 150px" value='<?php echo $fec2?>' /></td>
							<td><button type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
								</td>
						</tr>
					</table>
					</form>
				</div>		
				
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-list table-hover table-striped" width="100%">
						<thead id='tbl_thead'> 
							<tr>
								<th></th>
								<th colspan="4">Documento</th>
								<th colspan="3">Producto</th>
								<th>Transaccion</th>
								<th colspan="3">Ingreso</th>
								<th colspan="3">Egreso</th>
								<th colspan="3">Saldo</th>
							</tr>
							<tr>
								<th>No</th>
								<th>Fecha</th>
								<th>Documento No</th>
								<th>Guia Recepcion</th>
								<th>Proveedor</th>
								<th>Codigo</th>
								<th>Descripcion</th>
								<th>Unidad</th>
								<th>Tipo</th>
								<th>Cant.</th>
								<th>C.Unit</th>
								<th>C.Total</th>
								<th>Cant.</th>
								<th>C.Unit</th>
								<th>C.Total</th>
								<th>Cant.</th>
								<th>C.Unit</th>
								<th>C.Total</th>
							</tr>
						</thead>
						<tbody>
						<?php echo $detalle?>
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>


</section>

