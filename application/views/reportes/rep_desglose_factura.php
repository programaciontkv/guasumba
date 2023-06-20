<section class="content-header">
	  	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>rep_desglose_factura/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel2()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
       	<h1>
        Desglose de Facturas
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				
				<div class="col-md-10">
					<form id='frm_buscar' action="<?php echo $buscar;?>" method="post" >
						
					<table width="100%">
						<tr>
							<td class="hidden-mobile"><label>Buscar Por:</label></td>
							<td class="hidden-mobile"><input type="text" id='txt' name='txt' class="form-control" style="width: 150px" value='<?php echo $txt?>' placeholder="Factura/Producto/Cliente"/></td>
							<td><label>Local:</label></td>
							<td>
								<select id="emisor" name="emisor" class="form-control">
									<option value="0">SELECCIONE</option>
									<?php
									if(!empty($emisores)){
										foreach ($emisores as $emi) {
									?>
									<option value="<?php echo $emi->emi_id?>"><?php echo $emi->emi_nombre?></option>
									<?php		
										}
									}
									?>
									
								</select>
								<script>
									var emi='<?php echo $emisor?>';
									emisor.value=emi;
								</script>
							</td>
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control" style="width: 150px" value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control" style="width: 150px" value='<?php echo $fec2?>' /></td>
							<td><button type="button" class="btn btn-info" onclick="enviar()"><span class="fa fa-search" ></span> Buscar</button>
								</td>
						</tr>
					</table>
					</form>
				</div>			
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-striped table-list table-hover" width="100%">
						<thead id="tbl_thead">
								
							<tr>	
								<th class="hidden-mobile">No</th>
								<th>Fecha</th>
								<th>Factura No.</th>
								<th>Monto</th>
								<th>Cliente</th>
								<th class="hidden-mobile">Codigo</th>
								<th>Producto</th>
								<th>Cantidad</th>
								<th>Precio</th>
								<th>Descuento</th>
								<th>Valor</th>
							</tr>	
						</thead>
						<tbody>
						<?php
							$dec=$dec->con_valor;
							$dcc=$dcc->con_valor;
							$t_monto=0;
								$t_cnt=0;
								$t_prec=0;
								$t_desc=0;
								$t_val=0;
							if(!empty($facturas)){
								$n=0;

								$grup='';
								foreach ($facturas as $factura) {
									$n++;
						?>
								<tr>
									<?php
									if($grup!=$factura->fac_id){
									?>
									<td class="hidden-mobile"><?php echo $n?></td>
									<td><?php echo $factura->fac_fecha_emision?></td>
									<td><?php echo $factura->fac_numero?></td>
									<td class="number"><?php echo number_format($factura->fac_total_valor,$dec)?></td>
									<td><?php echo $factura->fac_nombre?></td>
									
									<?php
									$t_monto+=round($factura->fac_total_valor,$dec);
									}else{
									?>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<!-- <td></td> -->
									<?php
									}
									?>
									
									<td class="hidden-mobile"><?php echo $factura->mp_c?></td>
									<td><?php echo $factura->mp_d?></td>
									<td class="number"><?php echo number_format($factura->dfc_cantidad,$dcc)?></td>
									<td class="number"><?php echo number_format($factura->dfc_precio_unit,$dec)?></td>
									<td class="number"><?php echo number_format($factura->dfc_val_descuento,$dec)?></td>
									<td class="number"><?php echo number_format($factura->dfc_precio_total,$dec)?></td>
								</tr>

						<?php	
								$grup=$factura->fac_id;		
								
								$t_cnt+=round($factura->dfc_cantidad,$dec);
								$t_prec+=round($factura->dfc_precio_unit,$dec);
								$t_desc+=round($factura->dfc_val_descuento,$dec);
								$t_val+=round($factura->dfc_precio_total,$dec);
								
								}
							}
						?>
						<tr class="total">
								<th>Total</th>
								<th></th>
								<th></th>
								<th class="number"><?php echo number_format($t_monto,$dec)?></th>
								<th></th>
								<th></th>
								<th></th>
								<th class="number"><?php echo number_format($t_cnt,$dec)?></th>
								<th class="number"><?php echo number_format($t_prec,$dec)?></th>
								<th class="number"><?php echo number_format($t_desc,$dec)?></th>
								<th class="number"><?php echo number_format($t_val,$dec)?></th>
								
							</tr>
						</tbody>
					
					</table>
				</div>	
			</div>
		</div>
	</div>


</section>


<script>
	function enviar(){
		if($('#emisor').val()=='0'){
			alert('Seleccione Local');
		}else{
			$('#frm_buscar').submit();
		}
	}

	window.onload = function () {

      var mensaje ='<?php echo $mensaje;?>';
      if(mensaje != '')
      {

        swal("", mensaje, "info");
      }
      
    }
</script>