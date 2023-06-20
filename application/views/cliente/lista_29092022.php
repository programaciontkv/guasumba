<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
	
      <!-- <h1>
        Clientes Y Proveedores
      </h1> -->
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div>
				<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>cliente/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
			</div>
			<div class="row">

				<div class="col-md-2">
					<?php 
					if($permisos->rop_insertar){
					?>
						<a href="<?php echo base_url();?>cliente/nuevo/<?php echo $permisos->opc_id?>" class="btn btn-success btn-flat"><span class="fa fa-plus"></span> Crear Cliente </a>
					<?php 
					}
					?>
				</div>	
			
			<div class="col-md-6">
					<form action="<?php echo $buscar;?>" method="post">
						
					<table width="100%">
						<tr>
							<td><label>Buscar:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td><label>Estado:</label></td>
							<td><select name="estado" id="estado" class="form-control" style=
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
					<table id="tbl_list" class="table table-bordered table-list table-hover">
						<thead>
						<!-- 	<th>No</th> -->
							<th>RUC/CI</th>
							<th>Razón Social</th>
							<th>Ciudad</th>
							<th>Teléfono</th>
							<th>Email</th>
							<th>Estado</th>
							<th>Ajustes</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($clientes)){
							foreach ($clientes as $cliente) {
								$n++;
						?>
							<tr>
								<!-- <td><?php echo $n?></td> -->
								<td style="mso-number-format:'@'"><?php echo $cliente->cli_ced_ruc?></td>
								<td><?php echo $cliente->cli_raz_social?></td>
								<td><?php echo $cliente->cli_canton?></td>
								<td style="mso-number-format:'@'"><?php echo $cliente->cli_telefono?></td>
								<td style="text-transform: lowercase;"><?php echo $cliente->cli_email?></td>
								<!-- <td><?php echo $cliente->est_descripcion?></td>  -->

								<?php
								if($cliente->cli_estado == 1){

								?>
								<td>
								
									 <img title="Inactivar Cliente/Proveedor" width="40px" height="40px" onclick="cambiar_es(2,<?php echo $cliente->cli_id?>)" src="../imagenes/activo.png"> 
									
								</td>
								<?php
								}else{
								?>
								<td>
									 <img title="Activar Cliente/Proveedor" width="40px" height="40px" onclick="cambiar_es(1,<?php echo $cliente->cli_id?>)" src="../imagenes/inactivo.png"> 
									
								</td>
								<?php
								}
								?> 

								<td align="center">
									<div class="btn-group">
										<?php 
										if($permisos->rop_reporte){
										?>
											<button type="button" class="btn btn-info btn-view" data-toggle="modal" title="Ver Cliente/Proveedor" data-target="#modal-default" value="<?php echo base_url();?>cliente/visualizar/<?php echo $cliente->cli_id?>"><span class="fa fa-eye"></span>
								            </button>
							            <?php
							        	}
										if($permisos->rop_actualizar){
										?>
											<a href="<?php echo base_url();?>cliente/editar/<?php echo $cliente->cli_id?>/<?php echo $permisos->opc_id?>" title="Editar Cliente/Proveedor" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
										<?php 
										}
										if($permisos->rop_eliminar){
										?>
									<!-- 	<a title="Eliminar Cliente/Proveedor" href="<?php echo base_url();?>cliente/eliminar/<?php echo $cliente->cli_id?>/<?php echo $cliente->cli_ced_ruc.$cliente->cli_raz_social?>" class="btn btn-danger btn-remove"><span class="fa fa-trash"></span></a> -->
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
                <h4 class="modal-title">Clientes y Proveedores </h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>
<script type="text/javascript">
	function cambiar_es(estado,id){
		 var base_url='<?php echo base_url();?>';
		 var op = <?php echo $actual_opc; ?>;
		
		Swal.fire({
		  title: 'Desea cambiar de estado al cliente/proveedor ?',
		  showCancelButton: true,
		  confirmButtonText: 'Guardar',
		  denyButtonText: `Cancelar`,
		}).then((result) => {
		  /* Read more about isConfirmed, isDenied below */
		  if (result.isConfirmed) {

		    var  uri=base_url+"cliente/cambiar_estado/"+estado+"/"+id+"/"+op;
				      $.ajax({
				              url: uri,
				              type: 'POST',
				              success: function(dt){
				              	if(dt==1){
				              	   window.location.href = window.location.href;
				              	}else{
				              		swal("Error!", "No se pudo modificar .!", "warning");
				              	}
				                
				              } 
				        });

		  } else if (result.isDenied) {
		    // Swal.fire('No ha registrado cambios', '', 'info');
		  }
		})
	   
		 
	}
	
</script>