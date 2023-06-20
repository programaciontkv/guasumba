<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>egreso/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel2()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Egresos de Bodega
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
					
						<a href="<?php echo base_url();?>egreso/nuevo/9/<?php echo $opc_id?>" class="btn btn-success boton"><span class="fa fa-plus"></span> Egreso por Ajuste</a>
					
				</div>
				<div class="col-md-2">
					
						<a href="<?php echo base_url();?>egreso/nuevo/1/<?php echo $opc_id?>" class="btn btn-success boton"><span class="fa fa-plus"></span> Egreso a consumo</a>
					
				</div>
				<div class="col-md-2">
					
						<a href="<?php echo base_url();?>egreso/nuevo/21/<?php echo $opc_id?>" class="btn btn-success boton"><span class="fa fa-plus"></span> Egreso Correccion</a>
					
				</div>
				<div class="col-md-2">
					
						<a href="<?php echo base_url();?>egreso/nuevo/16/<?php echo $opc_id?>" class="btn btn-success boton"><span class="fa fa-plus"></span> Otro Egreso</a>
					
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
							<tr class="hidden-mobile">
								<th></th>
								<th colspan="4">Documento</th>
								<th colspan="3">Producto</th>
								<th colspan="4">Transaccion</th>
							</tr>
							<tr>
								<!-- <th>No</th> -->
								<th>Fecha</th>
								<th class="hidden-mobile">Documento No</th>
								<th class="hidden-mobile" >Documento/Informacion</th>
								<th>Proveedor</th>
								<th class="hidden-mobile" >Codigo</th>
								<th class="hidden-mobile" >Descripcion</th>
								<th class="hidden-mobile" >Unidad</th>
								<th class="hidden-mobile" >Tipo</th>
								<th  >Cantidad</th>
								<th class="hidden-mobile">Costo Unit</th>
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
						if(!empty($egresos)){
							foreach ($egresos as $egreso) {
								$n++;
								    
						?>
							<tr>
								<!-- <td><?php echo $n?></td> -->
								<td><?php echo $egreso->mov_fecha_trans?></td>
								<td class="hidden-mobile" style="mso-number-format:'@'"><?php echo $egreso->mov_documento?></td>
								<td class="hidden-mobile" style="mso-number-format:'@'"><?php echo $egreso->mov_guia_transporte?></td>
								<td><?php echo $egreso->cli_raz_social?></td>
								<td class="hidden-mobile" style="mso-number-format:'@'"><?php echo $egreso->mp_c?></td>
								<td class="hidden-mobile" ><?php echo $egreso->mp_d?></td>
								<td class="hidden-mobile" ><?php echo $egreso->mp_q?></td>
								<td class="hidden-mobile" ><?php echo $egreso->trs_descripcion?></td>
								<td  class="number"><?php echo str_replace(',','', number_format($egreso->mov_cantidad,$dcc))?></td>
								<td class="hidden-mobile" class="number"><?php echo str_replace(',','', number_format($egreso->mov_val_unit,$dec))?></td>
								<td class="hidden-mobile" class="number"><?php echo str_replace(',','', number_format($egreso->mov_val_tot,$dec))?></td>
								<?php
								if($grup!=$egreso->mov_documento){
									if($permisos->rop_reporte){
								?>
								<td>
										<a href="#" onclick="envio('<?php echo $egreso->mov_documento?>',1)" class="btn btn-warning" title="RIDE"> <span class="fa fa-file-pdf-o" ></span></a>
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
								$grup=$egreso->mov_documento;
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
			url="<?php echo base_url();?>egreso/show_frame/"+id+"/<?php echo $permisos->opc_id?>";
		}
		
		$('#frm_buscar').attr('action',url);
		$('#frm_buscar').submit();
	}
</script>