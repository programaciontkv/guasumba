<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>orden_compra/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel2()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Ordenes de Compra
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
					
						<a href="<?php echo base_url();?>orden_compra/nuevo/<?php echo $opc_id?>" class="btn btn-success boton"><span class="fa fa-plus"></span> Nueva Orden</a>
				</div>

				<?php 
					}
					?>
				<div>
					<br><br><br>
				</div>
				<div class="col-md-8" style="margin-left:-12px">
					<form action="<?php echo $buscar;?>" method="post" id="frm_buscar">
						
					<table width="100%">
						<tr>
							<td class="hidden-mobile" ><label>Buscar:</label></td>
							<td class="hidden-mobile" ><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td class="hidden-mobile" ><label>Estado:</label></td>
							<td>
								<select name="estado" id="estado" class="form-control" style="width: 200px">
									<option value="0">TODOS</option>
									<?php
									print_r($estados);
									if(!empty($estados)){
										foreach ($estados as $estado) {
									?>
										<option value="<?php echo $estado->est_id?>"><?php echo $estado->est_descripcion?></option>
									<?php		
										}
									}
									?>
								</select>
								<script>
									var est="<?php echo $est?>";
									estado.value=est;
								</script>
							</td>
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control"  value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control"  value='<?php echo $fec2?>' /></td>
							
						</tr>
					</table>
					
					<br>

					<div class="row">
						<div class="col-md-12">
							<button type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
						</div>
					</div>
					</form>
				</div>		
				
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-list table-hover table-striped" width="100%">
						<thead id='tbl_thead'>
							
							<tr>
								<!-- <th>No</th> -->
								<th>No. Orden</th>
								<th>Fecha</th>
								<th>Proveedor</th>
								<th>Subtotal 12%</th>
								<th>Subtotal 0%</th>
								<th class="hidden-mobile" >Desc %</th>
								<th class="hidden-mobile" >Desc $</th>
								<th>Iva 12 $</th>
								<th class="hidden-mobile" >Flete</th>
								<th>Total</th>
								<th>Estado</th>
								<th>Ajustes</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$dec=$dec->con_valor;
						$dcc=$dcc->con_valor;
						$n=0;
						$grup='';
						if(!empty($ordenes)){
							foreach ($ordenes as $orden) {
								$n++;
								    
						?>
							<tr>
								<!-- <td><?php echo $n?></td> -->
								<td style="mso-number-format:'@'"><?php echo $orden->orc_codigo?></td>
								<td><?php echo $orden->orc_fecha?></td>
								<td><?php echo $orden->cli_raz_social?></td>
								<td  class="number"><?php echo str_replace(',','', number_format($orden->orc_sub12,$dcc))?></td>
								<td  class="number"><?php echo str_replace(',','', number_format($orden->orc_sub0,$dcc))?></td>
								<td  class="number hidden-mobile"><?php echo str_replace(',','', number_format($orden->orc_descuento,$dcc))?></td>
								<td  class="number hidden-mobile"><?php echo str_replace(',','', number_format($orden->orc_descv,$dcc))?></td>
								<td  class="number"><?php echo str_replace(',','', number_format($orden->orc_iva,$dcc))?></td>
								<td  class="number"><?php echo str_replace(',','', number_format($orden->orc_flete,$dcc))?></td>
								<td  class="number"><?php echo str_replace(',','', number_format($orden->orc_total,$dcc))?></td>
								<td>
									<?php echo $orden->est_descripcion?>
								</td>
								<td>	
									<?php
										if($permisos->rop_actualizar){
											if($orden->orc_estado==7){
									?>
											<a href="<?php echo base_url();?>orden_compra/editar/<?php echo $orden->orc_id?>/<?php echo $opc_id?>" class="btn btn-primary" title="Editar"> <span class="fa fa-edit" ></span></a>
									<?php 
											}
										}
									
										if($permisos->rop_reporte){		
									?>		
										<a href="#" onclick="envio('<?php echo $orden->orc_id?>',1)" class="btn btn-warning" title="RIDE"> <span class="fa fa-file-pdf-o" ></span></a>
									<?php 
										}
									?>		
								</td>		
								
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
.boton{
/*	background: #000B75;*/
	color: #ffffff;
	width: 150px;
}	
</style>
</section>

<script type="text/javascript">
	function envio(id,opc){
		if(opc==0){
			url='<?php echo $buscar?>';
		}else if(opc==1){
			url="<?php echo base_url();?>orden_compra/show_frame/"+id+"/<?php echo $permisos->opc_id?>";
		}
		
		$('#frm_buscar').attr('action',url);
		$('#frm_buscar').submit();
	}
</script>