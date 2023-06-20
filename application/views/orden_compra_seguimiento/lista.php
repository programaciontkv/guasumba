<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>orden_compra_seguimiento/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel2()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Seguimiento de Ordenes de Compra
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
							<td class="hidden-mobile" ><label>Estado:</label></td>
							<td >
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
							<tr >
								<!-- <td><?php echo $n?></td> -->
								<td style="mso-number-format:'@'"><?php echo $orden->orc_codigo?></td>
								<td><?php echo $orden->orc_fecha?></td>
								<td><?php echo $orden->orc_fecha_entrega?></td>
								<td><?php echo $orden->cli_raz_social?></td>
								<td><?php echo $orden->orc_concepto?></td>
								<td><?php echo $orden->est_descripcion?></td>
								<td align="center">
									<div class="btn-group">
										<?php 
										
							        	if($permisos->rop_actualizar){
							        		if($orden->orc_estado!=22){
										?>
											<a href="#"  onclick="envio('<?php echo $orden->orc_id?>',2)"class="btn btn-primary"> <span class="fa fa-edit"></span></a>
											<a href="#"  onclick="terminar('<?php echo $orden->orc_id?>',3)"class="btn btn-success">Terminar</a>

										<?php
											} 
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
		}else if(opc==2){
			url="<?php echo base_url();?>orden_compra_seguimiento/editar/"+id+"/<?php echo $permisos->opc_id?>";
		}
		
		$('#frm_buscar').attr('action',url);
		$('#frm_buscar').submit();
	}

	function terminar(id){
		Swal.fire({
          title: '¿Desea cambiar a estado Terminado a la orden de compra?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Si',
          denyButtonText: 'No'
          }).then((result) => {
            if (result.isConfirmed) {
            	url="<?php echo base_url();?>orden_compra_seguimiento/cambiar_estado/"+id+"/<?php echo $permisos->opc_id?>";
              	$('#frm_buscar').attr('action',url);
				$('#frm_buscar').submit();
            } 
        })  	
	}
</script>