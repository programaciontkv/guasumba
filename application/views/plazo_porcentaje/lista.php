<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>bancos_tarjetas/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Plazos y Porcentajes
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-12">
					<?php 
					if($permisos->rop_insertar){
					?>
						<a href="<?php echo base_url();?>plazo_porcentaje/nuevo/<?php echo $permisos->opc_id?>" class="btn btn-success btn-flat"><span class="fa fa-plus"></span> Crear registro</a>
					<?php 
					}
					?>
				</div>	
			</div>
			<br>
			<div class="row" >
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-list table-hover" style="margin-left:-5px">
						<thead>
						<!-- 	<th>No</th> -->
							<th >Tipo</th>
							<th>Desde</th>
							<th>Hasta</th>
							<th>Porcentaje</th>
							<th class="hidden-mobile">Estado</th>
							<th>Ajustes</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($plazo_porcentaje)){
							foreach ($plazo_porcentaje as $plazo) {
								$n++;
								

				                switch ($plazo->pcv_tipo) {
									case "1": $tipo="TIEMPO"; break;
									case "2": $tipo="VALOR"; break;
				                   
				                }
								
						?>
							<tr>
								<!-- <td><?php echo $n?></td> -->
								<td><?php echo $tipo?></td>
								<td><?php echo $plazo->pcv_desde?></td>
								<td><?php echo $plazo->pcv_hasta?></td>
								<td><?php echo $plazo->pcv_porcentaje?></td>
							

							<?php
								if($plazo->pcv_estado == 1){

								?>
								<td class="hidden-mobile">
								
									 <img title="Inactivar Bancos/Tarjetas" width="40px" height="40px" onclick="cambiar_es(2,<?php echo $plazo->pcv_id?>)" src="../imagenes/activo.png"> 
									
								</td>
								<?php
								}else{
								?>
								<td class="hidden-mobile">
									 <img title="Activar Bancos/Tarjetas" width="40px" height="40px" onclick="cambiar_es(1,<?php echo $plazo->pcv_id?>)" src="../imagenes/inactivo.png"> 
									
								</td>
								<?php
								}
								?>
								<td align="center">
									<div class="btn-group">
										<?php 
										if($permisos->rop_reporte){
										?>
											<!-- <button title="Ver detalles" type="button" class="btn btn-info btn-view" data-toggle="modal" data-target="#modal-default" value="<?php echo base_url();?>bancos_tarjetas/visualizar/<?php echo $plazo->pcv_id?>"><span class="fa fa-eye"></span>
								            </button> -->
							            <?php
							        	}
										if($permisos->rop_actualizar){
										?>
											<a title="Editar Plazo/Porcentaje" href="<?php echo base_url();?>plazo_porcentaje/editar/<?php echo $plazo->pcv_id?>/<?php echo $permisos->opc_id?>" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
										<?php 
										}
										if($permisos->rop_eliminar){
										?>
										<!-- <a href="<?php echo base_url();?>bancos_tarjetas/eliminar/<?php echo $plazo->pcv_id?>/<?php echo $plazo->btr_descripcion?>" class="btn btn-danger btn-remove"><span class="fa fa-trash"></span></a> -->
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
                <h4 class="modal-title">Bancos, Tarjetas y Plazos </h4>
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
		  title: 'Desea cambiar de estado al registro?',
		  showCancelButton: true,
		  confirmButtonText: 'Guardar',
		  denyButtonText: `Cancelar`,
		}).then((result) => {
		  /* Read more about isConfirmed, isDenied below */
		  if (result.isConfirmed) {

		    var  uri=base_url+"plazo_porcentaje/cambiar_estado/"+estado+"/"+id+"/"+op;
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
<script>
 function mostrar(){
    if($('#btr_tipo').val()=='2'){
      $('#div_dias').prop('style','display:block');
    }
 } 
</script>