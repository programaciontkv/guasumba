<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
	
      <!-- <h1>
        Productos
      </h1> -->
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			<div>
				<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>producto_comercial/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
			</div>
			
			<div class="row">
				<div class="col-md-2">
					<?php 
					if($permisos->rop_insertar){
					?>
						<a href="<?php echo base_url();?>producto_comercial/nuevo/<?php echo $permisos->opc_id?>" class="btn btn-success btn-flat"><span class="fa fa-plus"></span> Crear Producto</a>
					<?php 
					}
					?>
				</div>	
			
			<div class="col-md-7">
					<form action="<?php echo $buscar;?>" method="post">
						
					<table width="100%">
						<tr>
							<td><label>Buscar:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td><label>Estado:</label></td>
							<td><select name="estado" id="estado" class="form-control" style=
								"width: 180px">
								<option value="">SELECCIONE</option>
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
							<!-- <th>No</th> -->
							<th>Familia</th>
							<th>Tipo</th>
							<th>Código</th>
							<th>Descripción</th>
							<th>Unidad</th>
							<th>Precio $</th>
							<th>Código Aux</th>
							<th>Imagen</th>
							<th>Estado</th>
							<th>Ajustes</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($productos)){
							foreach ($productos as $producto) {
								$n++;
								
						?>
							<tr>
								<!-- <td><?php echo $n?></td> -->
								<td><?php echo $producto->tps_nombre?></td>
								<td><?php echo $producto->tip_nombre?></td>
								<td style="mso-number-format:'@'"><?php echo $producto->mp_c?></td>
								<td><?php echo $producto->mp_d?></td>
								<td><?php echo $producto->mp_q?></td>
								<td><?php echo $producto->mp_e?></td>
								<td style="mso-number-format:'@'"><?php echo $producto->mp_n?></td>
								<td class="imagen"><img id="fotografia"  onclick="ver_img(<?php echo $producto->id?>)" class="fotografia" src="<?php echo base_url().'imagenes/'.$producto->mp_aa ?>" width="50px" height="50px" class="form-control"></td>
							<!-- 	<td><?php echo $producto->est_descripcion?></td> -->


								<?php
								if($producto->mp_i == 1){

								?>
								<td>
								
									 <img width="25px" height="25px" onclick="cambiar_es(2,<?php echo $producto->id?>)" src="../imagenes/activo.png"> 
									
								</td>
								<?php
								}else{
								?>
								<td>
									 <img width="25px" height="25px" onclick="cambiar_es(1,<?php echo $producto->id?>)" src="../imagenes/inactivo.png"> 
									
								</td>
								<?php
								}
								?>

								
								<td align="center">
									<div class="btn-group">
										<?php 
							        	if($permisos->rop_reporte){
										?>
											<button type="button" class="btn btn-info btn-view" title="Ver producto terminado " data-toggle="modal" data-target="#modal-default" value="<?php echo base_url();?>producto_comercial/visualizar/<?php echo $producto->id?>"><span class="fa fa-eye"></span>
								            </button>
										<?php 
										}
										if($permisos->rop_actualizar){
										?>
											<a href="<?php echo base_url();?>producto_comercial/editar/<?php echo $producto->id?>/<?php echo $permisos->opc_id?>" class="btn btn-primary" title="Editar producto terminado "> <span class="fa fa-edit"></span></a>
										<?php 
										}
										if($permisos->rop_eliminar){
										?>
									<!-- 	<a href="<?php echo base_url();?>producto_comercial/eliminar/<?php echo $producto->id?>/<?php echo $producto->mp_c?>" title="Eliminar producto terminado " class="btn btn-danger btn-remove"><span class="fa fa-trash"></span></a> -->
										<?php 
										}
										?>
										<a href="<?php echo base_url();?>producto_comercial/show_frame/<?php echo $producto->id?>/<?php echo $permisos->opc_id?>" title="Generar código de barras " class="btn btn-success btn-success"><span class="fa fa-barcode"></span></a>
											
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
                <h4 class="modal-title">Producto Terminado </h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>

<div class="modal fade" id="img">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="text-align:center;"> Producto Terminado </h4>
              </div>
              <div class="modal-body" id="foto">
             
              </div>
              <div class="modal-footer" >
              	<div style="float:right">
              		  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              		</div>
              
              </div>
            </div>
          </div>
</div>
<script type="text/javascript">
	 var base_url='<?php echo base_url();?>';
	function ver_img(id){

		$.ajax({
						url: base_url+"producto_comercial/ver_img/"+id,
						type: 'JSON',
						dataType: 'JSON',
						success: function (dt) {
						
						if (dt!='') {

							 $('#foto').html(dt.img);
							 $("#img").modal('show');
						}
					},
					 error : function(xhr, status) {
                    // alert("Ingrese una imagen del producto");
                    swal("Error!", "Ingrese una imagen del producto.!", "error");
                         
             }
      });

	}
</script>
<script type="text/javascript">
	function cambiar_es(estado,id){
		 var base_url='<?php echo base_url();?>';
		 var op = <?php echo $actual_opc; ?>;
		
		Swal.fire({
		  title: 'Desea cambiar de estado al producto?',
		  showCancelButton: true,
		  confirmButtonText: 'Guardar',
		  denyButtonText: `Cancelar`,
		}).then((result) => {
		  /* Read more about isConfirmed, isDenied below */
		  if (result.isConfirmed) {

		    var  uri=base_url+"servicio/cambiar_estado/"+estado+"/"+id+"/"+op;
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