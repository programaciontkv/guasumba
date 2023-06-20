<section class="content-header">
      <form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>pedido/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Pedidos a facturar
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			<div class="row">

				<div class="col-sm-2">
					<?php 
					$dec=$dec->con_valor;
					if($permisos->rop_insertar){
					?>
						<a style="width:120px" href="<?php echo base_url();?>pedido/nuevo/<?php echo $permisos->opc_id?>" class="btn btn-success btn-flat"><span class="fa fa-plus"></span> Crear pedido</a>
					<?php 
					}
					?>
				</div>
				<br>
				<div class="col-md-7">
					<form action="<?php echo $buscar;?>" method="post">
						
					<table style="margin-left:-20px" >
						<tr>
							<td class="hidden-mobile"><label>Buscar:</label></td>
							<td class="hidden-mobile"><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td class="hidden-mobile" ><label>Estado:</label></td>
							<td class="hidden-mobile"><select name="estado" id="estado" class="form-control" style=
								"width: 180px">
								<option value="">TODOS</option>
								<?php
								if(!empty($cns_estados)){
									foreach ($cns_estados as $rst_est) {
								?>
								<option value="<?php echo $rst_est->est_id?>"><?php echo $rst_est->est_descripcion?></option>
								<?php		
									}
								}
								?>
								<script type="text/javascript">
									var est='<?php echo $estado?>';
									estado.value=est;
								</script>
							</select></td>
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control"  value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control"  value='<?php echo $fec2?>' /></td>
							<td>
								</td>
						</tr>
					</table>
				
					

				</div>
				
			</div>
			
			
				<div class="row">
					<div class="col-sm-2">
					<button style="width:120px" type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
				</div>	
				</div>
	</form>
					
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-list table-hover" style="margin-left:-10px">
						<thead>
						<!-- 	<th>No</th> -->
							<th class="hidden-mobile">Fecha</th>
							<th>Orden de venta</th>
							<th class="hidden-mobile">Ruc/Cedula</th>
							<th>Cliente</th>
							<th class="hidden-mobile">Local</th>
							<th class="hidden-mobile" >Vendedor</th>
							<th class="hidden-mobile">Total Valor</th>
							<th class="hidden-mobile">Estado</th>
							<th  >Ajustes</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($pedidos)){
							foreach ($pedidos as $pedido) {
								$n++;
						?>
							<tr>
								<!-- <td><?php echo $n?></td> -->
								<td class="hidden-mobile"><?php echo $pedido->ped_femision?></td>
								<td  style='mso-number-format:"@"'><?php echo $pedido->ped_num_registro?></td>
								<td class="hidden-mobile" style='mso-number-format:"@"'><?php echo $pedido->ped_ruc_cc_cliente?></td>
								<td><?php echo $pedido->ped_nom_cliente?></td>
								<td class="hidden-mobile"><?php echo $pedido->emi_nombre?></td>
								<td class="hidden-mobile"><?php echo $pedido->vnd_nombre?></td>
								<td class="hidden-mobile" style="text-align: right;"><?php echo number_format($pedido->ped_total,$dec)?></td>
								<td class="hidden-mobile" >
									<?php 
									if($pedido->ped_estado == 17 || $pedido->ped_estado == 7 || $pedido->ped_estado == 15 || $pedido->ped_estado == 19 | $pedido->ped_estado == 13 ){
									?>
									<button type="button" class="btn btn-default btn btn-default btn-view" data-toggle="modal" data-target="#modal-default" value="<?php echo base_url();?>pedido/visualizar/<?php echo $pedido->ped_id?>/<?php echo $permisos->opc_id?>"><?php echo $pedido->est_descripcion?></button>
									<?php
									}else{
									?>
										<?php echo $pedido->est_descripcion?>
									<?php
									}
									?>
								</td>
								<td align="center">
									<div class="btn-group">
										<?php 
							        	if($permisos->rop_reporte){
										?>
											<a href="<?php echo base_url();?>pedido/show_frame/<?php echo $pedido->ped_id?>/<?php echo $permisos->opc_id?>" class="btn btn-warning"> <span class="fa fa-file-pdf-o"></span></a>
										<?php 
										}
										if($permisos->rop_actualizar && ($pedido->ped_estado == 7 || $pedido->ped_estado == 14 )){
										?>
											<a href="<?php echo base_url();?>pedido/editar/<?php echo $pedido->ped_id?>/<?php echo $opc_id?>" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
										<?php 
										}
										if($permisos->rop_eliminar){
										?>
										<!-- <a href="<?php echo base_url();?>pedido/eliminar/<?php echo $pedido->ped_id?>/<?php echo $pedido->ped_num_registro?>" class="btn btn-danger btn-anular-comp"><span class="fa fa-trash"></span></a> -->
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
	</div>


</section>

<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cambiar estado de pedido a :</h4>
              </div>
              <div class="modal-body">
              	 
              </div>
              <div class="modal-footer">
              	<button type="button" class="btn btn-success pull-left" onclick="save()">Guardar</button>
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>
<script type="text/javascript">
	function save(){

		 $('#frm_save').submit(); 



	}
</script>