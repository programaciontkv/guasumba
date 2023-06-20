<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>movimiento/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Movimientos <?php echo $titulo?>
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-1">
					<?php 
					if($permisos->rop_insertar){
					?>
						<a href="<?php echo base_url();?>movimiento/nuevo/<?php echo $opc_id?>" class="btn btn-primary btn-flat"><span class="fa fa-plus"></span> Nuevo</a>
					<?php 
					}
					?>
				</div>	
				<div class="col-md-8">
					<form action="<?php echo $buscar;?>" method="post">
						
					<table width="100%">
						<tr>
							<td><label>Buscar:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td>
								<select name="tipo" id="tipo" class="form-control" style="width: 200px">
									<option value="26">PRODUCTO TERMINADO</option>
								<!-- 	<option value="69">MATERIA PRIMA</option> -->
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
						<thead>
							<tr>
								<th></th>
								<th colspan="4">Documento</th>
								<th colspan="3">Producto</th>
								<th colspan="4">Transacción</th>
							</tr>
							<tr>
								<th>No</th>
								<th>Fecha</th>
								<th>Documento No</th>
								<th>Guia Recepción</th>
								<th>Proveedor</th>
								<th>Codigo</th>
								<th>Descripción</th>
								<th>Unidad</th>
								<th>Tipo</th>
								<th>Cantidad</th>
								<th>Costo Unit</th>
								<th>Costo Total</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$dec=$dec->con_valor;
						$dcc=$dcc->con_valor;
						$n=0;
						if(!empty($movimientos)){
							foreach ($movimientos as $movimiento) {
								$n++;
								    
						?>
							<tr>
								<td><?php echo $n?></td>
								<td><?php echo $movimiento->mov_fecha_trans?></td>
								<td style="mso-number-format:'@'"><?php echo $movimiento->mov_documento?></td>
								<td style="mso-number-format:'@'"><?php echo $movimiento->mov_guia_transporte?></td>
								<td><?php echo $movimiento->cli_raz_social?></td>
								<td style="mso-number-format:'@'"><?php echo $movimiento->mp_c?></td>
								<td><?php echo $movimiento->mp_d?></td>
								<td><?php echo $movimiento->mp_q?></td>
								<td><?php echo $movimiento->trs_descripcion?></td>
								<td class="number"><?php echo str_replace(',','', number_format($movimiento->mov_cantidad,$dcc))?></td>
								<td class="number"><?php echo str_replace(',','', number_format($movimiento->mov_val_unit,$dec))?></td>
								<td class="number"><?php echo str_replace(',','', number_format($movimiento->mov_val_tot,$dec))?></td>
							</tr>
						<?php
							}
						}
						?>
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>

<style>
td{
	font-size: 11px !important;
}
.number{
	text-align: right !important;
}	
</style>
</section>

