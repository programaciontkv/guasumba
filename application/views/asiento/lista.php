<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>asiento/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Asientos Contables
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-2">
					<?php 
					if($permisos->rop_insertar){
					?>
						<a href="<?php echo base_url();?>asiento/nuevo/<?php echo $permisos->opc_id?>" class="btn btn-success btn-flat"><span class="fa fa-plus"></span> Crear Asiento </a>
					<?php 
					}
					?>
				</div>	
			
				<div class="col-md-8">
					<form action="<?php echo $buscar;?>" method="post" id="frm_buscar">
						
					<table style="margin-left:-10px">
						<tr>
							<td class="hidden-mobile" ><label>Buscar:</label></td>
							<td class="hidden-mobile"  	><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td>
							</td>
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control"  value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control"  value='<?php echo $fec2?>' /></td>
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
					<table id="tbl_list" class="table table-bordered table-list table-hover" style="margin-left:-22px">
						<thead>
							<th>No</th>
							<th>Asiento</th>
							<th>Concepto</th>
							<th>Fecha Emision</th>
							<th>Debe</th>
							<th>Haber</th>
							<th>Estado</th>
							<th>Ajustes</th>
						</thead>
						<tbody>
						<?php
						$dec=$dec->con_valor; 
						$n=0;
						if(!empty($asientos)){
							foreach ($asientos as $asiento) {
								$n++;
								
						?>
							<tr>
								<td><?php echo $n?></td>
								<td style="mso-number-format:'@'"><?php echo $asiento->con_asiento?></td>
								<td><?php echo $asiento->con_concepto?></td>
								<td><?php echo $asiento->con_fecha_emision?></td>
								<td style="text-align: right;"><?php echo number_format($asiento->con_valor_debe,$dec)?></td>
								<td style="text-align: right;"><?php echo number_format($asiento->con_valor_haber,$dec)?></td>
								<?php
								if($asiento->con_estado == 1){

								?>
								<td>
								
									 <img width="40px" height="40px" onclick="cambiar_es(2,<?php echo $asiento->con_asiento?>)" src="../imagenes/activo.png"> 
									
								</td>
								<?php
								}else{
								?>
								<td>
									 <img width="40px" height="40px" onclick="cambiar_es(1,<?php echo $asiento->con_asiento?>)" src="../imagenes/inactivo.png"> 
									
								</td>
								<?php
								}
								?>

								<td align="center">
									<div class="btn-group">
										<?php 
										if($permisos->rop_reporte){
										?>
											<a href="#" onclick="envio('<?php echo $asiento->con_asiento?>',1)" class="btn btn-warning" title="RIDE"> <span class="fa fa-file-pdf-o" ></span></a>

							            <?php
							        	}
										if($permisos->rop_actualizar){
										?>
											<a href="<?php echo base_url();?>asiento/editar/<?php echo $asiento->con_asiento?>/<?php echo $permisos->opc_id?>" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
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
                <h4 class="modal-title">Asiento</h4>
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
		  title: 'Desea cambiar de estado al Asiento?',
		  showCancelButton: true,
		  confirmButtonText: 'Guardar',
		  denyButtonText: `Cancelar`,
		}).then((result) => {
		  /* Read more about isConfirmed, isDenied below */
		  if (result.isConfirmed) {

		    var  uri=base_url+"asiento/cambiar_estado/"+estado+"/"+id+"/"+op;
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

	function envio(id,opc){
		if(opc==0){
			url='<?php echo $buscar?>';
		}else if(opc==1){
			url="<?php echo base_url();?>asiento/show_frame/"+id+"/<?php echo $permisos->opc_id?>";
		}
		
		$('#frm_buscar').attr('action',url);
		$('#frm_buscar').submit();
	}
	
</script>