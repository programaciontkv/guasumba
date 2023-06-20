<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>orden_compra_aprobacion/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel2()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Aprobacion de Ordenes de Compra
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
				
				<div class="col-md-8" style="margin-left:-12px">
					<form action="<?php echo $buscar;?>" method="post" id="frm_buscar">
						
					<table width="100%">
						<tr>
							<td class="hidden-mobile" ><label>Buscar:</label></td>
							<td class="hidden-mobile" ><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control"  value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control"  value='<?php echo $fec2?>' /></td>
							<td><button type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button></td>
						</tr>
					</table>
					
					<br>

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
								<th>Fecha Orden</th>
								<th>Fecha Entrega</th>
								<th>Proveedor</th>
								<th>Concepto</th>
								<th>Observaciones</th>
								<th>Subtotal 12%</th>
								<th>Subtotal 0%</th>
								<th>Iva 12 $</th>
								<th>Total</th>
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
								$style='';
								if($orden->verifica==1){
									$style="style='background:#ffbfaa'";
								}    
						?>
							<tr <?php echo $style?>>
								<!-- <td><?php echo $n?></td> -->
								<td style="mso-number-format:'@'"><?php echo $orden->orc_codigo?></td>
								<td><?php echo $orden->orc_fecha?></td>
								<td><?php echo $orden->orc_fecha_entrega?></td>
								<td><?php echo $orden->cli_raz_social?></td>
								<td><?php echo $orden->orc_concepto?></td>
								<td><?php echo $orden->orc_obs?></td>
								<td  class="number"><?php echo str_replace(',','', number_format($orden->orc_sub12,$dcc))?></td>
								<td  class="number"><?php echo str_replace(',','', number_format($orden->orc_sub0,$dcc))?></td>
								<td  class="number"><?php echo str_replace(',','', number_format($orden->orc_iva,$dcc))?></td>
								<td  class="number"><?php echo str_replace(',','', number_format($orden->orc_total,$dcc))?></td>
								
								<td align="center">
									<div class="btn-group">
										<a href="<?php echo base_url();?>orden_compra_aprobacion/actualizar/<?php echo $orden->orc_id?>/<?php echo $permisos->opc_id?>/13" title="Aprobar" class="btn btn-success"><span class="fa fa-check"></span></a>
										<a href="<?php echo base_url();?>orden_compra_aprobacion/actualizar/<?php echo $orden->orc_id?>/<?php echo $permisos->opc_id?>/14" title="Rechazar" class="btn btn-danger"> <span class="fa fa-close"></span></a>

										<?php 
										
							        	if($permisos->rop_actualizar){
										?>
											<a href="<?php echo base_url();?>orden_compra_aprobacion/editar/<?php echo $orden->orc_id?>/<?php echo $opc_id?>" class="btn btn-primary"> <span class="fa fa-eye"></span></a>
										<?php 
										}
									?>
									</div>
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