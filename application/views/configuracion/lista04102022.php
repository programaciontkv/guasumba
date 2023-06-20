<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
      <h1>
        Configuracion General
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			<div class="row">
				<div class="col-md-12">
					<form  id="frm_save" role="form" action="<?php echo $action?>" method="post" autocomplete="off">
					<table id="tbl_list" class="table table-bordered table-list table-hover">
						<thead>
							<tr>
								<div class="row">
									<div class="col-md-12">
										<?php 
										if($permisos->rop_insertar){
										?>
											<button type="button" onclick="save()" class="btn btn-primary">Guardar</button>
										<?php 
										}
										?>
									</div>	
								</div>
							</tr>
							<tr>
								<th>No</th>
								<th>Descripcion</th>
								<th>Parametros</th>
							<!-- 	<th>Acciones</th> -->
							</tr>	
						</thead>
						<tbody>
							<tr>
								<td colspan="3">
									 <b>GENERALES</b>
								</td>
							</tr>

							<tr>
								<td>1</td>
								<td>NOMBRE DEL SISTEMA</td>
								<td><input type="text" name="nom_sistema" value="<?php echo $conf15->con_valor2?>" style="text-transform: lowercase;"></td>
							</tr>
							<tr>
								<td colspan="3">
									 <b>INVENTARIOS/PRODUCTOS-SERVICIOS</b>
								</td>
							</tr>
							<tr>
								<td>2</td>
								<td>VALIDAR EXISTENCIAS EN INVENTARIOS AL MOMENTO DE FACTURAR:</td>
								<?php
									if($conf3->con_valor==0){
										$chk_vi1="checked";
										$chk_vi2="";
									}else{
										$chk_vi1="";
										$chk_vi2="checked";
									}
								?>
								<td><input type="radio" name="val_inventario" id="val_inventario1" <?php echo $chk_vi1?> value="0"> SI 
									<input type="radio" name="val_inventario" onclick="alert()" id="val_inventario2" <?php echo $chk_vi2?> value="1"> NO</td>
								
							</tr>
							
							<tr>
								<td>3</td>
								<?php
									if($conf6->con_valor==0){
										$chk_ci1="checked";
										$chk_ci2="";
									}else{
										$chk_ci1="";
										$chk_ci2="checked";
									}
								?>
								<td>CONTROL DE INVENTARIOS AL MOMENTO DE FACTURAR, QUE VALIDE EXISTENCIAS:</td>
								<td><input type="radio" name="ctrl_inventario" id="ctrl_inventario1" <?php echo $chk_ci1?> value="0"> GENERAL
									<input type="radio" name="ctrl_inventario" id="ctrl_inventario2" <?php echo $chk_ci2?> value="1"> POR PTO EMISION</td>
							</tr>

							<tr>
								<td>4</td>
								<td>NÚMERO DE DECIMALES AL MOSTRAR CANTIDADES:</td>
								<td><input type="text" name="dec_cantidad" value="<?php echo $conf1->con_valor?>"></td>
								
							</tr>
							<tr>
								<td>5</td>
								<td>AUTOCODIFICACIÓN DE PRODUCTOS Y SERVICIOS:</td>
								<?php
									if($conf23->con_valor==0){
										$chk_auto1="checked";
										$chk_auto2="";
									}else{
										$chk_auto1="";
										$chk_auto2="checked";
									}
								?>
								<td><input type="radio" name="auto" id="auto1" <?php echo $chk_auto1?> value="0"> SI 
									<input type="radio" name="auto"  id="auto2" <?php echo $chk_auto2?> value="1"> NO</td>
								
							</tr>
							<tr>
								<td colspan="3">
									 <b>FACTURACIÓN</b>
								</td>
							</tr>
							<tr>
								<td>6</td>
								<td>NÚMERO DE DECIMALES EN VALORES MONETARIOS</td>
								<td><input type="text" name="dec_moneda" value="<?php echo $conf2->con_valor?>"></td>
							</tr>
							<tr>
								<td>7</td>
								<?php
									if($conf20->con_valor==0){
										$chk_pr1="checked";
										$chk_pr2="";
									}else{
										$chk_pr1="";
										$chk_pr2="checked";
									}
								?>
								<td>OPCIÓN PARA EDITAR PRECIOS AL FACTURAR</td>
								<td><input type="radio" name="precio" id="precio1" <?php echo $chk_pr1?> value="0"> BLOQUEADO 
									<input type="radio" name="precio" id="precio2" <?php echo $chk_pr2?> value="1"> DESBLOQUEADO</td>
							</tr>
							<tr>
								<td>8</td>
								<?php
									if($conf21->con_valor==0){
										$chk_ds1="checked";
										$chk_ds2="";
									}else{
										$chk_ds1="";
										$chk_ds2="checked";
									}
								?>
								<td>OPCIÓN PARA EDITAR DESCUENTOS AL MOMENTO DE FACTURAR</td>
								<td><input type="radio" name="descuento" id="descuento1" <?php echo $chk_ds1?> value="0"> BLOQUEADO 
									<input type="radio" name="descuento" id="descuento" <?php echo $chk_ds2?> value="1"> DESBLOQUEADO</td>
							</tr>
							<tr>
								<td>9</td>
								<?php
									if($conf22->con_valor==0){
										$chk_mp1="checked";
										$chk_mp2="";
									}else{
										$chk_mp1="";
										$chk_mp2="checked";
									}
								?>
								<td>OPCIÓN PARA INGRESAR MULTIPLES FORMAS DE PAGO EN FACTURACIÓN</td>
								<td><input type="radio" name="m_pagos" id="m_pago1" <?php echo $chk_mp1?> value="0"> SI 
									<input type="radio" name="m_pagos" id="m_pago2" <?php echo $chk_mp2?> value="1"> NO</td>
							</tr>
							<tr>
								<td colspan="3">
									 <b>FIRMA ELECTRÓNICA</b>
								</td>
							</tr>
							<tr>
								<td>10</td>
								<?php
									if($conf5->con_valor==0){
										$chk_amb1="checked";
										$chk_amb2="";
										$chk_amb3="";
									}else if($conf5->con_valor==1){
										$chk_amb1="";
										$chk_amb2="checked";
										$chk_amb3="";
									}else{
										$chk_amb1="";
										$chk_amb2="";
										$chk_amb3="checked";
									}
								?>
								<td>AMBIENTE DE FACTURACION ELECTRONICA</td>
								<td><input type="radio" name="ambiente" id="ambiente1" <?php echo $chk_amb1?> value="0"> NINGUNO
									<input type="radio" name="ambiente" id="ambiente2" <?php echo $chk_amb2?> value="1"> PRUEBAS 
									<input type="radio" name="ambiente" id="ambiente3" <?php echo $chk_amb3?> value="2"> PRODUCCION</td>
							</tr>
							<tr>
								<td>11</td>
								<td>FIRMA ELECTRONICA</td>
								<td></td>
								<td align="center">
									<!-- <div class="btn-group">
											<a href="<?php echo base_url();?>configuracion/firma" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
									</div> -->
								</td>
							</tr>
							<tr>
								<td colspan="3">
									 <b>ENVÍO DE DOCUMENTOS ELECTRÓNICOS POR MAIL:</b>
								</td>
							</tr>
							<tr>
								<td>12</td>
								<td>CREDENCIALES ENVIO MAIL</td>
								<?php 
									$em=explode('&',$conf8->con_valor2);
								?>
								<td><?php echo $em[3]?></td>
								<td align="center">
									<div class="btn-group">
											<a href="<?php echo base_url();?>configuracion/envio_mail" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									 <b>CONTABILIDAD:</b>
								</td>
							</tr>
							
							<tr>
								<td>13</td>
								<?php
									if($conf4->con_valor==0){
										$chk_as1="checked";
										$chk_as2="";
									}else{
										$chk_as1="";
										$chk_as2="checked";
									}
								?>
								<td>GENERAR ASIENTOS CONTABLES</td>
								<td><input type="radio" name="asientos" id="asiento1" <?php echo $chk_as1?> value="0"> SI 
									<input type="radio" name="asientos" id="asiento2" <?php echo $chk_as2?> value="1"> NO</td>
							</tr>
							
							
						</tbody>
					</table>
				</form>
				</div>	
			</div>
		</div>
	</div>
	<script type="text/javascript">

	
	function alert(){
		

		if ($('#val_inventario2').prop('checked') == true) {
		 			Swal.fire("Aviso!", "Ha retirado la validación de existencias en facturación.\n Esto puede provocar inconvenientes o negativos en bodega.!", "warning");
		 		}
	}
		
		function save(){


			 	Swal.fire({
		  	title: '¿Esta seguro guardar la información?',
			  showCancelButton: true,
			  confirmButtonText: 'Guardar',
			  denyButtonText: `Cancelar`,
				}).then((result) => {
		  		
		 		 if (result.isConfirmed) {
		  			$('#frm_save').submit();
		  		} else if (result.isDenied) {
		  			///accion si no
		  		}
				})
                   
           
		}
		
	</script>


</section>

