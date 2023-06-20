<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
	  	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>cheque/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
       	<h1>
        Control de Cobros <?php echo $titulo?>
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			<div class="row">
				<div class="col-md-1">
					<?php 
					if($permisos->rop_insertar){
					?>
						<a href="<?php echo base_url();?>cheque/nuevo/<?php echo $permisos->opc_id?>" class="btn btn-primary btn-flat"><span class="fa fa-plus"></span> Nuevo</a>
					<?php 
					}
					?>
				</div>	
				<div class="col-md-8">
					<form action="<?php echo $buscar;?>" method="post" id="frm_buscar">
						
					<table width="100%">
						<tr>
							<td><label>Buscar:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control" style="width: 150px" value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control" style="width: 150px" value='<?php echo $fec2?>' /></td>
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
					<table id="tbl_list" class="table table-bordered table-list table-hover" width="100%">
						<thead>
							<!-- <th>No</th> -->
							<th>Fecha Cobro</th>
							<th class="hidden-mobile">Fecha Registro</th>
							<th>Tipo Documento</th>
							<th>Cliente</th>
							<th class="hidden-mobile">Nombre del Cheque</th>
							<!-- <th>Banco</th> -->
							<th class="hidden-mobile">No Cheque</th>
							<th>$ Valor</th>
							<th class="hidden-mobile">$ Saldo</th>
							<th>Estado Cobro</th>
							<th>Estado Cheque</th>
							<th >Ajuste</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($cheques)){
							foreach ($cheques as $cheque) {
								$n++;
								$saldo=round($cheque->chq_monto,$dec)-round($cheque->chq_cobro,$dec);
								switch ($cheque->chq_tipo_doc) {
                                    case '1': $tp="TARJETA DE CREDITO"; break;
                                    case '2': $tp="TARJETA DE DEBITO"; break;
                                    case '3': $tp="CHEQUE A LA FECHA"; break;
                                    case '4': $tp="EFECTIVO"; break;
                                    case '5': $tp="CERTIFICADOS"; break;
                                    case '6': $tp="TRANSFERENCIA"; break;
                                    case '7': $tp="RETENCION"; break;
                                    case '8': $tp="NOTA CREDITO"; break;
                                    case '9': $tp="CREDITO"; break;
                                    case '10': $tp="CHEQUE POSTFECHADO"; break;
                                  }
						?>			
							<tr>
								<!-- <td><?php echo $n?></td> -->
								<td><?php echo $cheque->chq_fecha?></td>
								<td class="hidden-mobile"><?php echo $cheque->chq_recepcion?></td>
								<td><?php echo $tp?></td>
								<td style="mso-number-format:'@'"><?php echo $cheque->cli_raz_social?></td>
								<td class="hidden-mobile" style="mso-number-format:'@'"><?php echo $cheque->chq_nombre?></td>
								<!-- <td style="mso-number-format:'@'"><?php echo $cheque->pln_descripcion?></td> -->
								<td class="hidden-mobile"  style="mso-number-format:'@'"><?php echo $cheque->chq_numero?></td>
								<td class="number"><?php echo str_replace(',', '', number_format($cheque->chq_monto,$dec))?></td>
								<td class="number hidden-mobile"><?php echo str_replace(',', '', number_format($saldo,$dec))?></td>
								<td><?php echo $cheque->est_descripcion?></td>
								<td>
									<?php 
									if($cheque->chq_estado_cheque!=12 && $cheque->chq_estado_cheque!=3){
									?>
									<!-- <a href="<?php echo base_url();?>cheque/cambiar_estado/<?php echo $cheque->chq_id?>/<?php echo $opc_id?>" class="btn btn-default" title="Cambiar Estado"><?php echo $cheque->est_cheque?></a> -->
									<?php echo $cheque->est_cheque?>
									<?php
									}else{
									?>
									<?php echo $cheque->est_cheque?>
									<?php
									}
									?>
								</td>
								<td   align="center">
									<div >
										<?php
										if($permisos->rop_actualizar){
											if(($cheque->chq_estado==7 && $cheque->chq_estado_cheque==10 )|| ($saldo == $cheque->chq_monto && $cheque->chq_estado !=3 && $cheque->chq_estado_cheque != 12  ))
											{
										?>
												<a href="<?php echo base_url();?>cheque/editar/<?php echo $cheque->chq_id?>/<?php echo $opc_id?>" class="btn btn-primary" title="Editar"> <span class="fa fa-edit" ></span></a>
										<?php 
											}
										}
										?>

										<?php 
										if($cheque->chq_estado_cheque==11 && $cheque->chq_estado!=9){
										?>	
										<a href="<?php echo base_url();?>cheque/cobrar/<?php echo $cheque->chq_id?>/<?php echo $opc_id?>/<?php echo$cheque->cli_id?>" class="btn btn-info" title="Cobrar"> <span class="fa fa-table" ></span></a>
										<?php
										}	
										?>

										
										<?php 
							        	if($permisos->rop_reporte && ($saldo != $cheque->chq_monto  ) ){
										?>
											<a href="#" onclick="envio('<?php echo $cheque->chq_id?>',1)" class="btn btn-warning" title="RIDE"> <span class="fa fa-file-pdf-o" ></span></a>

											<?php 
										}
											if ($cheque->chq_estado_cheque==7 || $cheque->chq_estado_cheque==11 &&  $cheque->chq_tipo_doc !=4 ) {
											?>
											<!-- <a href="#" onclick="cambiar_es('<?php echo $cheque->chq_id?>',<?php echo $opc_id?>,3,'ANULADO')" class="btn btn-success" title="Anular Cheque"> 
												<span class="fa fa-ban" ></span></a> -->

											<a href="#" onclick="venta_est('<?php echo $cheque->chq_id?>','<?php echo $cheque->chq_estado?>',<?php echo $opc_id?>,<?php echo $saldo?>,
											'<?php echo $cheque->chq_monto?>')" class="btn btn-success" title="Anular Cheque"> 
												<span class="fa fa-hourglass-start" ></span></a>

												<?php 
											}
												?>
											

										
										
										<?php
									
										if(($cheque->chq_estado==7 && $cheque->chq_estado_cheque==10 )|| ($saldo == $cheque->chq_monto && $cheque->chq_estado !=3 && $cheque->chq_estado_cheque != 12 ))
											{
										?>
												<!-- <a href="<?php echo base_url();?>cheque/eliminar/<?php echo $cheque->chq_id?>/<?php echo $opc_id?>" class="btn btn-danger" title="Editar"> <span class="fa fa-times" ></span></a> -->
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
<div class="modal fade" id="modal-default" >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pagos Realizados</h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>

<div class="modal fade" id="estados" >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cambiar de estado</h4>
              </div>
              <div class="modal-body">

              	 <div class="form-group">

											<select name="chq_estado_cheque"  id="chq_estado_cheque" class="form-control">
              	 			</select>
              	 			<input type="hidden" id="id" name="id">
              	 			<!-- <input type="text" id="estado" name="estado"> -->
              	 </div>


                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success pull-left" onclick="cambiar_es()" >Guardar</button>
              </div>
            </div>
          </div>
</div>
<style type="text/css">
	.custom-button {
        font-size: 0.9rem !important;
        }
</style>
<script type="text/javascript">

var mensaje = '<?php echo $mensaje ;?>';

var base_url='<?php echo base_url();?>';

if(mensaje != ''){
// swal("", mensaje, "info");
swal.fire("Info!", mensaje, "info");
}
	function envio(id,opc){
		if(opc==0){
			url='<?php echo $buscar?>';
		}else if(opc==1){
			url="<?php echo base_url();?>cheque/show_frame/"+id+"/<?php echo $permisos->opc_id?>";
		}
		
		$('#frm_buscar').attr('action',url);
		$('#frm_buscar').submit();
	}

function venta_est(id,est,opc,saldo,monto){
	$("#estados").modal('show');
	var selectobject = document.getElementById("chq_estado_cheque");
	$("#chq_estado_cheque").empty();

	$("#estado").val(est);
	$("#id").val(id);
	$("#chq_estado_cheque").val('');

	if (saldo==monto) {
		$("#chq_estado_cheque").prepend("<option value='0'>ELIMINAR</option>");
	}
	if(est == 3){
		for (var i=0; i<selectobject.length; i++) {
		if (selectobject.options[i].value == '3')
		selectobject.remove(i);
		}
	}
	if(est == 12){
		for (var i=0; i<selectobject.length; i++) {
		if (selectobject.options[i].value == '12')
		selectobject.remove(i);
		}
	}

	if (saldo != monto) {
		$("#chq_estado_cheque").prepend("<option value='12'>PROTESTADO</option>");
		$("#chq_estado_cheque").prepend("<option value='3'>ANULADO</option>");
	}


		
		$("#chq_estado_cheque").prepend("<option value=''>ESCOJER UNA OPCION</option>");

		$("#chq_estado_cheque").val('');

												
											
}
	function cambiar_es(){
		 var base_url= '<?php echo base_url();?>';
		 var opc     = '<?php echo $opc_id;?>';
		 var combo   = document.getElementById("chq_estado_cheque");
		 var ley     = combo.options[combo.selectedIndex].text;
		 var id 	   = $("#id").val();
		 var estado  = $("#chq_estado_cheque").val();

		
		Swal.fire({
		  title: 'Desea cambiar al estado '+ley+'?',
		  text :'Observación:',
			input: 'text',
			inputAttributes: {
			autocapitalize: 'none',
			},
			customClass: {
            text: 'custom-button',
        },
		  showCancelButton: true,
		  confirmButtonText: 'Guardar',
		  denyButtonText: `Cancelar`,
		}).then((result) => {

		  obs = result.value;
		  if (result.isConfirmed) {

		  	if (estado!=0) {
		  		var  uri=base_url+"cheque/cambiar_est/"+opc;
		  	}
		  	else{
		  		var  uri=base_url+"cheque/eliminar/"+id+"/"+opc;
		  	}

		    

		    var parametros = {
        "chq_id"             :  id,
        "chq_estado_cheque"  :  estado,
        "chq_est_observacion":  obs,
         };

				      $.ajax({
				              url: uri,
				              type: 'POST',
				              	data:  parametros, //datos que se envian a traves de ajax
				              success: function(dt){

												if (estado!=0) {
															if(dt==1){
						              		
						              	   //window.location.href = window.location.href;
						              	   window.location.reload();
						              	   $("#estados").modal('hide');
						              	}else{
						              		swal.fire("Error!", "No se pudo modificar .!", "warning");
						              	}
												}
												else{
													 window.location.reload();
						              $("#estados").modal('hide');
												}

				              	
				                
				              } 
				        });
				    

		  } else if (result.isCancel) {
		     //Swal.fire('No ha registrado cambios', '', 'info');
		  }
		})
	   
		 
	}
</script>

