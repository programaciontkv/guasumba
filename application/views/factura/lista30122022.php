<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
	<div class="row" style="float:right;padding:10px;margin: 0px;" >
		<div class="col-md-5">
			<form id="exp_excel"  method="post" action="<?php echo base_url();?>factura/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
		</div>
		<div class="col-md-5">
			<input type="submit" onclick="exportar_zip()" value="ZIP" class="btn btn-primary" />
		</div>
	</div>
	  
       	<h1>
        Facturas Emitidas
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
						<a href="<?php echo base_url();?>factura/nuevo/<?php echo $permisos->opc_id?>" class="btn btn-success btn-flat"><span class="fa fa-plus"></span> Crear Factura </a>
					<?php 
					}
					?>
				</div>
				<br>
				<!-- <p style='justify-content: right !important;'>$mensaje </p> -->
				<div class="col-md-8">
					<form action="<?php echo $buscar;?>" method="post">
						
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
							<!-- <td><button type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
								</td> -->
						</tr>
					</table>
					<!-- </form> -->
				</div>			
			</div>
			<div class="row">
					<div class="col-sm-2">
					<button style="width:120px" type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
				</div>	
				</div>
	</form>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" style="margin-left:-10px" class="table table-bordered table-list table-hover">
						<thead>
							<!-- <th>No</th> -->
							<th class="hidden-mobile">Fecha</th>
							<th>Factura</th>
							<th class="hidden-mobile">Ruc</th>
							<th>Cliente</th>
							<th >Total Valor</th>
							<th class="hidden-mobile">Vendedor</th>
							<th class="hidden-mobile" >Estado</th>
							<th>Ajustes</th>
							<th class="hidden-mobile">ZIP <input type='checkbox' id='chk_todos' onclick="seleccionar()" /></th>
						</thead>
						<tbody>
						<?php 
				        $dec=$dec->con_valor;
						$n=0;
						$t_f=0;
						if(!empty($facturas)){

							foreach ($facturas as $factura) {
								$n++;

								$t_f+=$factura->fac_total_valor;
						?>
							<tr>
							<!-- 	<td><?php echo $n?></td> -->
								<td class="hidden-mobile"><?php echo $factura->fac_fecha_emision?></td>
								<td><?php echo $factura->fac_numero?></td>
								<td class="hidden-mobile" style="mso-number-format:'@'"><?php echo $factura->fac_identificacion?></td>
								<td><?php echo $factura->fac_nombre?></td>
								<td  style="text-align: right;"><?php echo number_format($factura->fac_total_valor,$dec)?></td>
								<td class="hidden-mobile"><?php echo $factura->vnd_nombre?></td>
								<td class="hidden-mobile"><?php echo $factura->est_descripcion?></td>
								<td align="center">
									<div class="btn-group">
										
										<a href="<?php echo base_url();?>factura/show_ftalon/<?php echo $factura->fac_id?>/<?php echo $permisos->opc_id?>" class="btn btn-success" title="Talon"> <span class="fa fa-bookmark-o" ></span></a>

										<?php 
										if($factura->fac_estado==6 ){
										?>
											

											<!-- <a href="#" class="btn btn-success" onclick="envio_mail(<?php echo $factura->fac_id?>,<?php echo $permisos->opc_id?>)" title="Envio Correo"> <span  class="fa  fa-envelope"></span></a> -->

											<a href="#" class="btn btn-success" onclick="mail('<?php echo $factura->fac_email ?>',<?php echo $factura->fac_identificacion?>,'<?php echo $factura->fac_nombre?>',<?php echo $factura->fac_id?>,<?php echo $permisos->opc_id?>)" title="Envio Correo"> <span  class="fa  fa-envelope"></span></a>
										


										<?php 
										}
										
										if($factura->fac_estado==4 || $factura->fac_estado==6){
										?>

											<a href="../xml_docs/<?php echo $factura->fac_clave_acceso?>.xml" download="../xml_docs/<?php echo $factura->fac_clave_acceso?>.xml" class="btn btn-info" > <span title="XML">XML</span></a>
										<?php 
										}
							        	if($permisos->rop_reporte){
										?>
											<a href="<?php echo base_url();?>factura/show_frame/<?php echo $factura->fac_id?>/<?php echo $permisos->opc_id?>" class="btn btn-warning" title="RIDE"> <span class="fa fa-file-pdf-o" ></span></a>
										<?php 
										}
										if($permisos->rop_actualizar){
											if($factura->fac_estado!=3 && $factura->fac_estado!=6){
										?>
												<!-- <a href="<?php echo base_url();?>factura/editar/<?php echo $factura->fac_id?>/<?php echo $opc_id?>" class="btn btn-primary" title="Editar"> <span class="fa fa-edit" ></span></a> -->
										<?php 
											}
										}
										if($permisos->rop_eliminar){
											if($factura->fac_estado!=3){
										?>
												<a href="<?php echo base_url();?>factura/anular/<?php echo $factura->fac_id?>/<?php echo $factura->fac_numero?>/<?php echo $permisos->opc_id?>" class="btn btn-danger btn-anular-comp" title="Anular"><span class="fa fa-times" ></span></a>
										<?php 
											}
										}
										?>

									</div>
								</td>
								<td class="hidden-mobile" align="center"><input type='checkbox' id='chk<?php echo $factura->fac_id?>' class='chk' lang='<?php echo $factura->fac_id?>'/></td>  
							</tr>
						<?php
							}
						}
						?>
						<tr>
							<td  class="hidden-mobile"></td>
							<td></td>
							<td></td>
							<td> <h4>Total facturado</h4></td>
							<td><h4  style="text-align: right;"><?php echo number_format($t_f,2) ;?> </h4> </td>
						</tr>
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
                <h4 class="modal-title">Factura</h4>
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
	
	function seleccionar(){

                if($('#chk_todos').prop('checked') == true){

                    $('.chk').each(function () {

                        $(this).attr('checked',true);
                    })
                }else{
                    $('.chk').each(function () {
                        $(this).attr('checked',false);
                    })
                }
            }
function exportar_zip(){
    var datos=Array();
    $('.chk').each(function () {

        if($(this).prop('checked')==true){

            datos.push(this.lang);

        }
    })


		$.ajax({
			beforeSend: function () {
												if (datos.length == 0) {
												      alert("Seleccione al menos una factura");
												      return false;
												  }
                            },
		              url: '<?php echo base_url();?>factura/zip_facturas/',
		              type: 'POST',
		              data: { 'data[]': datos},
		              success: function (dt) {
		              	//alert(dt);
                   		window.location = '<?php echo base_url();?>'+dt+'.zip';
		                      
		              },
		              error : function(xhr, status) {
		                    
		              }
		              });

}

function envio_mail(id,opc){
	var ruta=base_url+"factura/consulta_sri/"+id+"/"+opc;
        $.ajax({
            url: ruta,
            type: 'GET',
            timeout: 15000,
            success: function(resp){
                alert(resp);
            },
            error: function (j, t, e) {
                        if (t == 'timeout') {
                            alert('Tiempo agotado No se pudo enviar correo');
                        }else{
                        	alert(e);
                        }
            }
        });
}

function mail(correo,ruc,nombre,id,opc){
	
	Swal.fire({
title: '¿Esta seguro reenviar el documento al correo? \n'+ correo,
showDenyButton: true,
showCancelButton: true,
denyButtonText: `Reenviar a otro correo`,
confirmButtonText: 'Si, reenviar',
denyButtonText: `Reenviar a otro correo`,
}).then((result) => {
	
	 if (result.isConfirmed) {
		envio_mail(id,opc);
	} else if (result.isDenied) {
		reenviar_otro(ruc,nombre,id,opc);
	}
})
                   
           
 }

 function reenviar_otro(ruc,nombre,id,opc){
	
Swal.fire({
  title: 'Ingrese el/los correos destinatarios separados por ;',
  input: 'text',
  inputAttributes: {
    autocapitalize: 'none',
   
  },
  showCancelButton: true,
  //showDenyButton: true,
  confirmButtonText: 'Reenviar ',
  cancelButtonText: `Cancelar`,
  showLoaderOnConfirm: true,
  }).then((result) => {
  	 email = result.value;
	 if (result.isConfirmed) {
	 	reenviar(id,opc,1,'Factura.pdf',1,email,nombre);
	}
})                 
           
 }

function reenviar(id,opc,correo,etq,tipo,email,nombre){

	Swal.fire({
title: '¿Reenvio de correo',
showDenyButton: true,
showCancelButton: true,
denyButtonText: `Reenviar y modificar email del cliente `+nombre,
confirmButtonText: 'Solo reenviar',
}).then((result) => {
	
	 if (result.isConfirmed) {		
		tipo = 1;
		var ruta=base_url+"factura/show_pdf_2/"+id+"/"+opc+"/"+correo+"/"+etq+"/"+tipo;
		var parametros = {
        "id"     :  id,
        "opc"    :  opc,
        "correo" :  correo,
        "etq"    :  etq,
        "tipo"   :  tipo,
        "email"  :  email,
         };
        $.ajax({
            url: ruta,
            type: 'POST',
            data:  parametros, //datos que se envian a traves de ajax
            timeout: 15000,
            success: function(resp){
                alert(resp);
            },
            error: function (j, t, e) {
                        if (t == 'timeout') {
                            alert('Tiempo agotado No se pudo enviar correo');
                        }else{
                        	alert(e);
                        }
            }
        });
	} else if (result.isDenied) {
		tipo = 2;
		var ruta=base_url+"factura/show_pdf_2/"+id+"/"+opc+"/"+correo+"/"+etq+"/"+tipo;
		var parametros = {
        "id"     :  id,
        "opc"    :  opc,
        "correo" :  correo,
        "etq"    :  etq,
        "tipo"   :  tipo,
        "email"  :  email,
         };
        $.ajax({
            url: ruta,
            type: 'POST',
            data:  parametros, //datos que se envian a traves de ajax
            timeout: 15000,
            success: function(resp){
                alert(resp);
            },
            error: function (j, t, e) {
                        if (t == 'timeout') {
                            alert('Tiempo agotado No se pudo enviar correo');
                        }else{
                        	alert(e);
                        }
            }
        });
	}
})

	
}





</script>