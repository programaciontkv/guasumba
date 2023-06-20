<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>ingreso/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel2()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Ingresos a Bodega
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<?php 
					if($permisos->rop_insertar){
					?>
				<div class="col-md-2">
					
						<a href="<?php echo base_url();?>ingreso/nuevo/28/<?php echo $opc_id?>" class="btn btn-success boton"><span class="fa fa-plus"></span> Ingreso Compra</a>
					
				</div>
				<div class="col-md-2">
					
						<a href="<?php echo base_url();?>ingreso/nuevo/3/<?php echo $opc_id?>" class="btn btn-success boton"><span class="fa fa-plus"></span> Ingreso Inventario</a>
					
				</div>
				<div class="col-md-2">
					
						<a href="<?php echo base_url();?>ingreso/nuevo/14/<?php echo $opc_id?>" class="btn btn-success boton"><span class="fa fa-plus"></span> Ingreso Correccion</a>
					
				</div>
				<div class="col-md-2">
					
						<a href="<?php echo base_url();?>ingreso/nuevo/8/<?php echo $opc_id?>" class="btn btn-success boton"><span class="fa fa-plus"></span> Otro Ingreso</a>
					
				</div>	

				<?php 
					}
					?>
				<div>
					<br><br><br>
				</div>
				
				<div class="col-md-8">
					<form action="<?php echo $buscar;?>" method="post">
						
					<table width="100%">
						<tr>
							<td  class="hidden-mobile" ><label>Buscar:</label></td>
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
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control"  value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control"  value='<?php echo $fec2?>' /></td>
							<br>
							
						</tr>

					</table>
					</form>
					<br>
					<div class="row">
						<div class="col-md-12">
							<button type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
						</div>
						
						
					</div>
				</div>		
				
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-list table-hover table-striped " width="100%">
						<thead id='tbl_thead'>
							<tr class="hidden-mobile">
								<th ></th>
								<th   colspan="4">Documento</th>
								<th  colspan="3">Producto</th>
								<th  colspan="4">Transaccion</th>
							</tr>
							<tr>
							<!-- 	<th>No</th> -->
								<th>Fecha</th>
								<th>Documento No</th>
								<th class="hidden-mobile" >Documento/Informacion</th>
								<th >Proveedor</th>
								<th class="hidden-mobile">Codigo</th>
								<th class="hidden-mobile">Descripcion</th>
								<th class="hidden-mobile">Unidad</th>
								<th class="hidden-mobile">Tipo</th>
								<th >Cantidad</th>
								<th class="hidden-mobile" >Costo Unit</th>
								<th class="hidden-mobile">Costo Total</th>
								<th>Ajustes</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$dec=$dec->con_valor;
						$dcc=$dcc->con_valor;
						$n=0;
						$grup='';
						if(!empty($ingresos)){
							foreach ($ingresos as $ingreso) {
								$n++;
								    
						?>
							<tr>
								<!-- <td><?php echo $n?></td> -->
								<td><?php echo $ingreso->mov_fecha_trans?></td>
								<td style="mso-number-format:'@'"><?php echo $ingreso->mov_documento?></td>
								<td class="hidden-mobile" style="mso-number-format:'@'"><?php echo $ingreso->mov_guia_transporte?></td>
								<td><?php echo $ingreso->cli_raz_social?></td>
								<td class="hidden-mobile" style="mso-number-format:'@'"><?php echo $ingreso->mp_c?></td>
								<td class="hidden-mobile"><?php echo $ingreso->mp_d?></td>
								<td class="hidden-mobile"><?php echo $ingreso->mp_q?></td>
								<td class="hidden-mobile"><?php echo $ingreso->trs_descripcion?></td>
								<td  class="number"><?php echo str_replace(',','', number_format($ingreso->mov_cantidad,$dcc))?></td>
								<td class="hidden-mobile" class="number"><?php echo str_replace(',','', number_format($ingreso->mov_val_unit,$dec))?></td>
								<td class="hidden-mobile" class="number"><?php echo str_replace(',','', number_format($ingreso->mov_val_tot,$dec))?></td>
								<?php
								if($grup!=$ingreso->mov_documento){
									if($permisos->rop_reporte){
								?>
								<td>
										<a href="<?php echo base_url();?>ingreso/show_frame/<?php echo $ingreso->mov_documento?>/<?php echo $permisos->opc_id?>" class="btn btn-warning" title="RIDE"> <span class="fa fa-file-pdf-o" ></span></a>
								</td>		
								<?php 
									}
								
								}else{
								?>
								<td></td>
								<?php
								}
								?>
							</tr>
						<?php
								$grup=$ingreso->mov_documento;
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

.boton{
	/*background: #000B75;*/
	color: #ffffff;
	width: 150px;
}	
</style>
</section>

