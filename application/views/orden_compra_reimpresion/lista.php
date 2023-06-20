<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>orden_compra_reimpresion/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel2()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Reimpresion de Etiquetas de Ordenes de Compra
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
								<th>Codigo</th>
								<th>Descripcion</th>
								<th>Unidad</th>
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
							<tr >
								<!-- <td><?php echo $n?></td> -->
								<td style="mso-number-format:'@'"><?php echo $orden->orc_codigo?></td>
								<td><?php echo $orden->orc_fecha?></td>
								<td><?php echo $orden->orc_fecha_entrega?></td>
								<td><?php echo $orden->cli_raz_social?></td>
								<td><?php echo $orden->orc_concepto?></td>
								<td style="mso-number-format:'@'"><?php echo $orden->mp_c?></td>
								<td><?php echo $orden->mp_d?></td>
								<td><?php echo $orden->mp_q?></td>
								<td align="center">
									<div class="btn-group">
										<?php 
										
							        	if($permisos->rop_reporte){
										?>
											<a href="#"  onclick="envio('<?php echo $orden->orc_det_id?>',1)"class="btn btn-warning"> <span class="fa fa-file-pdf-o"></span></a>
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
			url="<?php echo base_url();?>orden_compra_reimpresion/show_frame/"+id+"/<?php echo $permisos->opc_id?>";
		}
		
		$('#frm_buscar').attr('action',url);
		$('#frm_buscar').submit();
	}
</script>