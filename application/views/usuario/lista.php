<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>usuario/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Usuarios
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			<div class="row">
				<div class="col-md-12">
					<a href="<?php echo base_url();?>usuario/nuevo/<?php echo $permisos->opc_id?>" class="btn btn-success btn-flat"><span class="fa fa-plus"></span> Crear Usuario</a>
				</div>	
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-list table-hover">
						<thead>
							<!-- <th>No</th> -->
							<th class="hidden-mobile">Imagen</th>
							<th class="hidden-mobile">Nombre</th>
							<th>Usuario</th>
							<th>Rol</th>
							<th>Estado</th>
							<th>Ajustes</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($usuarios)){
							foreach ($usuarios as $usuario) {
								$n++;
								if ($usu_sesion == 1) {
									// code...
								
						?>
							<tr>
							<!-- 	<td><?php echo $n?></td> -->
								<td class="hidden-mobile" class='imagen'><img id="fotografia" class="fotografia" src="<?php echo base_url().'imagenes/'.$usuario->usu_imagen; ?>" width="100px" height="100px" class="form-control"></td>
								<td class="hidden-mobile"><?php echo $usuario->usu_person?></td>
								<td><?php echo $usuario->usu_login?></td>
								<td><?php echo $usuario->rol_nombre?></td>
								<!-- <td><?php echo $usuario->est_descripcion?></td> -->

								<?php
								if($usuario->usu_estado == 1){

								?>
								<td>
								
									 <img width="40px" height="40px" onclick="cambiar_es(2,<?php echo $usuario->usu_id?>)" src="../imagenes/activo.png"> 
									
								</td>
								<?php
								}else{
								?>
								<td>
									 <img width="40px" height="40px" onclick="cambiar_es(1,<?php echo $usuario->usu_id?>)" src="../imagenes/inactivo.png"> 
									
								</td>
								<?php
								}
								?>

								<td align="center">
									<div class="btn-group">
										<?php
										if($permisos->rop_reporte){
										?>
										<button type="button" class="btn btn-info btn-view" data-toggle="modal" data-target="#modal-default" value="<?php echo base_url();?>usuario/visualizar/<?php echo $usuario->usu_id?>"><span class="fa fa-eye"></span>
							            </button>
							            <?php 
										}
										if($permisos->rop_actualizar){
										?>
										<a href="<?php echo base_url();?>usuario/editar/<?php echo $usuario->usu_id?>/<?php echo $permisos->opc_id?>" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
										<?php 
										}
										if($permisos->rop_eliminar){
										?>
										<a href="<?php echo base_url();?>usuario/eliminar/<?php echo $usuario->usu_id?>/<?php echo $usuario->usu_person?>/<?php echo $permisos->opc_id?>" class="btn btn-danger btn-remove" ><span class="fa fa-trash"></span></a>
										<?php 
										}
										?>
									</div>
								</td>
							</tr>
						<?php
							}

							if ($usu_sesion != 1 && $usuario->usu_id != 1 ) {
									// code...
								
						?>
							<tr>
								<td><?php echo $n?></td>
								<td class='imagen'><img id="fotografia" class="fotografia" src="<?php echo base_url().'imagenes/'.$usuario->usu_imagen; ?>" width="100px" height="100px" class="form-control"></td>
								<td><?php echo $usuario->usu_id?></td>
								<td><?php echo $usuario->usu_login?></td>
								<td><?php echo $usuario->rol_nombre?></td>
								<td><?php echo $usuario->est_descripcion?></td>
								<td align="center">
									<div class="btn-group">
										<?php
										if($permisos->rop_reporte){
										?>
										<button type="button" class="btn btn-info btn-view" data-toggle="modal" data-target="#modal-default" value="<?php echo base_url();?>usuario/visualizar/<?php echo $usuario->usu_id?>"><span class="fa fa-eye"></span>
							            </button>
							            <?php 
										}
										if($permisos->rop_actualizar){
										?>
										<a href="<?php echo base_url();?>usuario/editar/<?php echo $usuario->usu_id?>/<?php echo $permisos->opc_id?>" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
										<?php 
										}
										if($permisos->rop_eliminar){
										?>
<a href="<?php echo base_url();?>usuario/eliminar/<?php echo $usuario->usu_id?>/<?php echo $usuario->usu_person?>/<?php echo $permisos->opc_id?>" class="btn btn-danger btn-remove" ><span class="fa fa-trash"></span></a>
										<?php 
										}
										?>
									</div>
								</td>
							</tr>
						<?php
							}


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
                <h4 class="modal-title">Usuario</h4>
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
		  title: 'Desea cambiar de estado al usuario?',
		  showCancelButton: true,
		  confirmButtonText: 'Guardar',
		  denyButtonText: `Cancelar`,
		}).then((result) => {
		  /* Read more about isConfirmed, isDenied below */
		  if (result.isConfirmed) {

		    var  uri=base_url+"usuario/cambiar_estado/"+estado+"/"+id+"/"+op;
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