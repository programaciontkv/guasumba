<section class="content-header">
	  	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>rep_desglose_factura/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel2()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
       	<h1>
        Pedidos vs Facturas
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
							<td><label>Buscar Por:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 150px" value='<?php echo $txt?>' placeholder="Pedido/Factura/Cliente"/></td>
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
								<th>No</th>
								<th>Fecha</th>
								<th>Pedido No.</th>
								<th>Cliente</th>
								<th>Subtotal</th>
								<th>Descuento</th>
								<th>Sub0%</th>
								<th>Sub12%</th>
								<th>Iva</th>
								<th>Total</th>
								<th>Fecha</th>
								<th>Factura No.</th>
								<th>Subtotal</th>
								<th>Descuento</th>
								<th>Sub0%</th>
								<th>Sub12%</th>
								<th>Iva</th>
								<th>Total</th>
							</tr>	
						</thead>
						<tbody>
						<?php
							$dec=$dec->con_valor;
							$dcc=$dcc->con_valor;
							$tp_sub=0;
							$tp_des=0;
							$tp_sub0=0;
							$tp_sub12=0;
							$tp_iva=0;
							$tp_total=0;
							$tf_sub=0;
							$tf_des=0;
							$tf_sub0=0;
							$tf_sub12=0;
							$tf_iva=0;
							$tf_total=0;
							if(!empty($pedidos)){
								$n=0;

								$grup='';
								foreach ($pedidos as $pedido) {
									$n++;
									if($pedido->fac_fecha_emision=='1990-01-01'){
										$pedido->fac_fecha_emision='';
									}
						?>
								<tr>
									<?php
									if($grup!=$pedido->ped_id){
										$sub0=round($pedido->ped_sbt0,$dec)+round($pedido->ped_sbt_noiva,$dec)+round($pedido->ped_sbt_excento,$dec);
										$fsub0=round($pedido->fac_subtotal0,$dec)+round($pedido->fac_subtotal_ex_iva,$dec)+round($pedido->fac_subtotal_no_iva,$dec);
									?>
									<td><?php echo $n?></td>
									<td><?php echo $pedido->ped_femision?></td>
									<td><?php echo $pedido->ped_num_registro?></td>
									<td><?php echo $pedido->ped_nom_cliente?></td>
									<td class="number"><?php echo number_format($pedido->ped_sbt,$dec)?></td>
									<td class="number"><?php echo number_format($pedido->ped_tdescuento,$dec)?></td>
									<td class="number"><?php echo number_format($sub0,$dec)?></td>
									<td class="number"><?php echo number_format($pedido->ped_sbt12,$dec)?></td>
									<td class="number"><?php echo number_format($pedido->ped_iva12,$dec)?></td>
									<td class="number"><?php echo number_format($pedido->ped_total,$dec)?></td>
									<?php
									$tp_sub+=round($pedido->fac_subtotal,$dec);
									$tp_des+=round($pedido->fac_total_descuento,$dec);
									$tp_sub0+=round($fsub0,$dec);
									$tp_sub12+=round($pedido->fac_subtotal12,$dec);
									$tp_iva+=round($pedido->fac_total_iva,$dec);
									$tp_total+=round($pedido->ped_total,$dec);
									}else{
									?>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<?php
									}
									?>
									<td><?php echo $pedido->fac_fecha_emision?></td>
									<td><?php echo $pedido->fac_numero?></td>
									<td class="number"><?php echo number_format($pedido->fac_subtotal,$dec)?></td>
									<td class="number"><?php echo number_format($pedido->fac_total_descuento,$dec)?></td>
									<td class="number"><?php echo number_format($fsub0,$dec)?></td>
									<td class="number"><?php echo number_format($pedido->fac_subtotal12,$dec)?></td>
									<td class="number"><?php echo number_format($pedido->fac_total_iva,$dec)?></td>
									<td class="number"><?php echo number_format($pedido->fac_total_valor,$dec)?></td>
									
								</tr>

						<?php
									
									$tf_sub+=round($pedido->fac_subtotal,$dec);
									$tf_des+=round($pedido->fac_total_descuento,$dec);
									$tf_sub0+=round($fsub0,$dec);
									$tf_sub12+=round($pedido->fac_subtotal12,$dec);
									$tf_iva+=round($pedido->fac_total_iva,$dec);
									$tf_total+=round($pedido->fac_total_valor,$dec);	
									$grup=$pedido->ped_id;		
								}
							}
						?>
						<tr class="total">
								<th>Total</th>
								<th></th>
								<th></th>
								<th></th>
								<th class="number"><?php echo number_format($tp_sub,$dec)?></th>
								<th class="number"><?php echo number_format($tp_des,$dec)?></th>
								<th class="number"><?php echo number_format($tp_sub0,$dec)?></th>
								<th class="number"><?php echo number_format($tp_sub12,$dec)?></th>
								<th class="number"><?php echo number_format($tp_iva,$dec)?></th>
								<th class="number"><?php echo number_format($tp_total,$dec)?></th>
								<th></th>
								<th></th>
								<th class="number"><?php echo number_format($tf_sub,$dec)?></th>
								<th class="number"><?php echo number_format($tf_des,$dec)?></th>
								<th class="number"><?php echo number_format($tf_sub0,$dec)?></th>
								<th class="number"><?php echo number_format($tf_sub12,$dec)?></th>
								<th class="number"><?php echo number_format($tf_iva,$dec)?></th>
								<th class="number"><?php echo number_format($tf_total,$dec)?></th>
								
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
</script>