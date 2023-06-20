<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
	  	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>ctasxcobrar/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>

       	
       	<input type="button" value="PDF" class="btn btn-warning"  onclick="validar()" style="float:right;"/>
       	<h1>
        Central de Cobranzas <?php echo $titulo?>
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-12">
					<form id="frm_buscar" action="<?php echo $buscar;?>" method="post" >
						
					<table width="100%">
						<tr>
							<td><label>Buscar:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 160px" value='<?php echo $txt?>'/></td>
							<td style="display: none;"><input type="date" id='fec1' name='fec1' class="form-control" style="width: 150px" value='<?php echo $fec1?>' /></td>
							<td><label>Estado:</label></td>
							<td colspan="3">
								<?php 
									if($vencer=='on'){
										$chk_vencer='checked';
									}else{
										$chk_vencer='';
									}

									if($vencido=='on'){
										$chk_vencido='checked';
									}else{
										$chk_vencido='';
									}

									if($pagado=='on'){
										$chk_pagado='checked';
									}else{
										$chk_pagado='';
									}
								?>
								<input type="checkbox" id='vencer' name='vencer' <?php echo $chk_vencer?>/><label>Por Vencer</label> 
								<input type="checkbox" id='vencido' name='vencido' <?php echo $chk_vencido?>/><label>Vencido </label>
								<input type="checkbox" id='pagado' name='pagado' <?php echo $chk_pagado?>/><label>Pagado</label> 
							</td>
							<td><label>Al:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control" style="width: 150px" value='<?php echo $fec2?>' /></td>
							<td><button type="buttom" class="btn btn-info" onclick="envio(0)"><span class="fa fa-search"></span> Buscar</button>
								</td>
							<td>
								<button type="buttom" class="btn btn-primary" onclick="envio(2)" title="Reporte Saldo x clientes">Saldo x clientes</button>
							</td>
							<td>
								<button type="buttom" class="btn btn-primary" onclick="envio(3)" title="Reporte Cartera vencida">Cartera vencida</button>
							</td>
							<td>
								
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
							<th>No</th>
							<th>Factura</th>
							<th>Fecha Emision</th>
							<th>Fecha Vencimiento</th>
							<th>$ Total</th>
							<th>$ Pagado</th>
							<th>$ Saldo</th>
							<th>Estado</th>
							<th>Acciones</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						$fecha=$fec2;
						$grup="";
						$t_credito=0;
						$t_debito=0;
						$t_saldo=0;
						if(!empty($facturas)){
							foreach ($facturas as $factura) {
								$n++;
								$saldo=round($factura->fac_total_valor,$dec)-round($factura->pago,$dec);
								if($saldo>0 && $factura->pag_fecha_v>$fecha){
									$estado='POR VENCER';
								} else if($saldo>0 && $factura->pag_fecha_v<$fecha){
									$estado='VENCIDO';
								} else if($saldo<=0){
									$estado='PAGADO';
								}

								if($grup!=$factura->fac_identificacion && $n!=1){
						?>			
									<tr class='success'>
										<td class='total' colspan="3" style='font-weight: bolder;'></td>
										<td class='total' style='font-weight: bolder;'> TOTAL</td>
										<td class='number total' style='font-weight: bolder;'><?php echo str_replace(',','', number_format($t_credito,$dec))?></td>
										<td class='number total' style='font-weight: bolder;'><?php echo str_replace(',','', number_format($t_debito,$dec))?></td>
										<td class='number total' style='font-weight: bolder;'><?php echo str_replace(',','', number_format($t_saldo,$dec))?></td>
										<td class='total' colspan="2" style='font-weight: bolder;'> </td>
									</tr>
						<?php
								$t_credito=0;
								$t_debito=0;
								$t_saldo=0;				
								}
								
						?>
							
						<?php
							if($grup!=$factura->fac_identificacion){
						?>		
								
							<tr>
								<td></td>
								<td><a href="#" onclick="envio(1,'<?php echo $factura->fac_identificacion?>')"> <?php echo $factura->fac_nombre?></a></td>
								<td style="mso-number-format:'@'"><a href="#" onclick="envio(4,'<?php echo $factura->fac_identificacion?>')"> <?php echo $factura->fac_identificacion?></a></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td>
									
								</td>
								<td>
									<a href="#" onclick="envio(7,'<?php echo $factura->fac_identificacion?>')" class="btn btn-danger" title="Reporte Mora"> Mora</a>
									<a href="#" onclick="envio(8,'<?php echo $factura->fac_identificacion?>')" class="btn btn-danger" title="Reporte Estado de Cobros">Estado Cobros</a>

									<a href="#" onclick="envio_estados('<?php echo $factura->fac_identificacion?>')" class="btn btn-success" title="Envio reportes">Envio reportes</a></td>
							</tr>
						<?php		
							}
						?>
						
							<tr <?php echo $estado?>>
								<td><?php echo $n?></td>				
								<td><a href="#" onclick="envio(5,<?php echo $factura->fac_id?>)"> <?php echo $factura->fac_numero?></a></td>
								<td><?php echo $factura->fac_fecha_emision?></td>
								<td><?php echo $factura->pag_fecha_v?></td>
								<td class="number"><?php echo str_replace(',', '', number_format($factura->fac_total_valor,$dec))?></td>
								<td class="number"><?php echo str_replace(',', '', number_format($factura->pago,$dec))?></td>
								<td class="number"><?php echo str_replace(',', '', number_format($saldo,$dec))?></td>
								<td><?php echo $estado?></td>
								<td align="center">
									<div class="btn-group">
										<?php 
							        	if($permisos->rop_reporte){
										?>
										<a href="#" onclick="envio(6,'<?php echo $factura->fac_id?>')" class="btn btn-info" title="Detalle"> <span class="fa fa-file-pdf-o" ></span></a>
											
										<?php
										}	
										
									/*	if($permisos->rop_insertar){
										?>
												<a href="<?php echo base_url();?>ctasxcobrar/nuevo/<?php echo $opc_id?>/<?php echo $factura->fac_id?>" class="btn btn-primary" title="Pagos"> <span class="fa fa-edit" ></span></a>
										<?php 
											}*/
										?>
									</div>
								</td>
							</tr>
						<?php
								$grup=$factura->fac_identificacion;
								$t_credito+=round($factura->fac_total_valor,$dec);
								$t_debito+=round($factura->pago,$dec);
								$t_saldo+=round($saldo,$dec);
							}
						}
						?>
								<tr class='success'>
										<td class='total' colspan="3" style='font-weight: bolder;'></td>
										<td class='total' style='font-weight: bolder;'> TOTAL</td>
										<td class='number total' style='font-weight: bolder;'><?php echo str_replace(',','', number_format($t_credito,$dec))?></td>
										<td class='number total' style='font-weight: bolder;'><?php echo str_replace(',','', number_format($t_debito,$dec))?></td>
										<td class='number total' style='font-weight: bolder;'><?php echo str_replace(',','', number_format($t_saldo,$dec))?></td>
										<td class='total' colspan="2" style='font-weight: bolder;'> </td>
								</tr>
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

<div class="modal fade" id="envio">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="limpiar_cliente()" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" style="text-align:center;">Envio de reportes</h3>
              </div>
              <div class="modal-body">
              	<input type="hidden" name="cliente" id="cliente">
                <table class="table table-bordered table-striped" id="det_clientes">
                 <tr>

                 	<td>Remitente</td>
                 	<td><?php echo $doc_mail ?></td>
                 </tr>
                 <tr>
                 	<td>Destinatario:</td>
                 	<td><textarea id="destinatario" name="destinatario" style="height: 80px; width: 90%;" >PROGRAMACION@TIKVAS.COM</textarea> </td>
                 </tr>
                 <tr>
                 	<td>Asunto:</td>
                 	<td><textarea id="asunto" name="asunto" style="height: 80px; width: 90%;"></textarea> </td>
                 </tr>
                 <tr> 
                 	<td>Elija los reportes a enviar:</td>
                 	<tr>
                 		<td>
                 			<input type="checkbox" id='mora' name='mora' ><label>Estado por Mora</label>
                 		</td>
                 		<td>
                 			<input type="checkbox" id='std_cobros' name='std_cobros' ><label>Estado por Cobros</label>
                 		</td>
                 	</tr>
                 	<tr>
                 	<td>
                 			<input type="checkbox" id='sta_1' name='sta_1' ><label>Estado de cuenta1 </label>
                 		</td>
                 		<td>
                 			<input type="checkbox" id='sta_2' name='sta_2' ><label>Estado de cuenta 2</label>
                 		</td>
                 		
                 	</tr>
                
                 	
                 </tr>
                  
                </table>
                <button onclick="envio_doc()" class=" btn btn-success">Envio</button>
              </div>

               <div class="modal-footer"  >
                <div style="float:right">
               
                <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button> -->
                </div>
                

              </div>
              
            </div>
          </div>
</div>

<script type="text/javascript">
	var base_url = '<?php echo base_url();?>';
	var opc_id = '<?php echo $opc_id;?>';

	function envio_doc(){
	
	correo=$("#destinatario").val();

Swal.fire({
title: '¿Esta seguro reenviar estos documentos  al correo? \n'+ correo,
showDenyButton: false,
showCancelButton: true,
confirmButtonText: 'Si, enviar',
denyButtonText: `Reenviar a otro correo`,
}).then((result) => {
	
	 if (result.isConfirmed) {

	 	var mora;
	 	var std_cobros ;
	 	var sta_1;
	 	var sta_2;
		var fec2       = $("#fec2").val();
		var id 				 = $("#cliente").val();
		var asunto 		 = $("#asunto").val();
	 	
	 	if (document.getElementById('mora').checked)
			{
				mora = 'on';
			}else{
				mora = 'off';
			}

		if (document.getElementById('std_cobros').checked)
			{
				std_cobros = 'on';
			}else{
				std_cobros = 'off';
			}

		if (document.getElementById('sta_1').checked)
			{
				sta_1 = 'on';
			}else{
				sta_1 = 'off';
			}

		if (document.getElementById('sta_2').checked)
			{
				sta_2 = 'on';
			}else{
				sta_2 = 'off';
			}


	 	var ruta=base_url+"ctasxcobrar/envio_doc/";
		var parametros = {
        "id"        :  id,
        "mora"      :  mora,
        "std_cobros":  std_cobros,
        "sta_1"     :  sta_1,
        "sta_2"     :  sta_2,
        "fec2"      :  fec2,
        "correo"    :  correo,
        "asunto"    :  asunto,
        "opc_id"    :  opc_id
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

	}
})
                   
           
 }

	function envio_estados(cliente){
		$("#cliente").val(cliente);
		$("#envio").modal('show');
	}
	function envio(opc,ci){
		if(opc==0){
			url='<?php echo $buscar?>';
		}else if(opc==1){
			url="<?php echo base_url();?>ctasxcobrar/buscar_reporte/"+ci+"/<?php echo $permisos->opc_id?>/1";
		}else if(opc==2){
			url="<?php echo base_url();?>ctasxcobrar/buscar_reporte/0/<?php echo $permisos->opc_id?>/2";
		}else if(opc==3){
			url="<?php echo base_url();?>ctasxcobrar/buscar_reporte/0/<?php echo $permisos->opc_id?>/3";
		}else if(opc==4){
			url="<?php echo base_url();?>ctasxcobrar/buscar_reporte/"+ci+"/<?php echo $permisos->opc_id?>/5";
		}else if(opc==5){
			url="<?php echo base_url();?>factura/show_frame/"+ci+"/<?php echo $permisos->opc_id?>";
		}else if(opc==6){
			url="<?php echo base_url();?>ctasxcobrar/show_frame/"+ci+"/<?php echo $permisos->opc_id?>";
		}else if(opc==7){
			url="<?php echo base_url();?>ctasxcobrar/buscar_reporte/"+ci+"/<?php echo $permisos->opc_id?>/0";
		}else if(opc==8){
			url="<?php echo base_url();?>ctasxcobrar/buscar_reporte/"+ci+"/<?php echo $permisos->opc_id?>/6"
		}
		$('#frm_buscar').attr('action',url);
		$('#frm_buscar').submit();

	}


function validar(){
		
			url="<?php echo base_url();?>ctasxcobrar/show_rpt_pdf/<?php echo $permisos->opc_id?>";
			$('#frm_buscar').attr('action',url);
			$('#frm_buscar').submit();
		
	}</script>