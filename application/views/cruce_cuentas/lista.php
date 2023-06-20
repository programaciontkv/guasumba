<section class="content-header">
	  	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>ctasxpagar/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
       	<h1>
        Cruce de Cuentas
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-9">
					<form action="<?php echo $buscar;?>" method="post">
						
					<table width="100%">
						<tr>
							<td><label>Buscar:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control" style="width: 150px" value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control" style="width: 150px" value='<?php echo $fec2?>' /></td>
							<td><button type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
								</td>
								<td><label>Estado:</label></td>
							<td class="hidden-mobile"><select name="estado" id="estado" class="form-control" style=
								"width: 180px">
								<option value="">TODOS</option>
								<?php
								var_dump($estados);
								if(!empty($estados)){
									
									foreach ($estados as $rst_est) {
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
							<th>Ruc</th>
							<th>Proveedor</th>
							<th>Factura</th>
							<th>Fecha Emision</th>
							<th>Fecha Vencimiento</th>
							<th>Total</th>
							<th>Pagado</th>
							<th>Saldo</th>
							<th>Estado Pago</th>
							<th>Estado Factura</th>
							<th>Acciones</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						$fecha=date('Y-m-d');
						$grup="";
						$t_credito=0;
						$t_debito=0;
						$t_saldo=0;
						if(!empty($facturas)){
							foreach ($facturas as $factura) {
								
								$reg_factura_model   = new Reg_factura_model();
								$cruce_cuentas_model = new Cruce_cuentas_model();
								$caja_model          = new Caja_model();
								$opcion_model        = new Opcion_model();
								$rst_opc             = $opcion_model->lista_una_opcion($opc_id);
								$reg_factura         = $reg_factura_model->lista_una_factura($factura->reg_id);
								$rst_cja             = $caja_model->lista_una_caja($rst_opc->opc_caja);
		                        $cantidad            = $cruce_cuentas_model->lista_facturas_cliente_contador($reg_factura->cli_id,$rst_cja->emp_id);
		                        if($cantidad->n>0)
		                        {


 

								$n++;

								$saldo=round($factura->reg_total,$dec)-round($factura->pago,$dec);
								$estado='';
								if($saldo>0 && $factura->pag_fecha_v>=$fecha){
									$estado='POR VENCER';
								} else if($saldo>0 && $factura->pag_fecha_v<$fecha){
									$estado='VENCIDO';
								} else if($saldo<=0){
									$estado='PAGADO';
								}

								if($grup!=$factura->cli_ced_ruc && $n!=1){
						?>			
									<tr class='success'>
										<td class='total' colspan="5" style='font-weight: bolder;'></td>
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
							<tr <?php echo $estado?>>
								<td><?php echo $n?></td>
						<?php
							if($grup!=$factura->cli_ced_ruc){
						?>		
								
								<td style="mso-number-format:'@'"><?php echo $factura->cli_ced_ruc?></td>
								<td><?php echo $factura->cli_raz_social?></td>
						<?php		
							}else{
						?>
								<td></td>
								<td></td>
						<?php		
							}
						?>					
								<td><?php echo $factura->reg_num_documento?></td>
								<td><?php echo $factura->reg_femision?></td>
								<td><?php echo $factura->pag_fecha_v?></td>
								<td class="number"><?php echo str_replace(',', '', number_format($factura->reg_total,$dec))?></td>
								<td class="number"><?php echo str_replace(',', '', number_format($factura->pago,$dec))?></td>
								<td class="number"><?php echo str_replace(',', '', number_format($saldo,$dec))?></td>
								<td><?php echo $estado?></td>
								<td><?php echo $factura->est_descripcion?></td>
								<td align="center">
									<div class="btn-group">
										<?php
										if($permisos->rop_insertar){
										?>
												<a href="<?php echo base_url();?>cruce_cuentas/nuevo/<?php echo $opc_id?>/<?php echo $factura->reg_id?>" class="btn btn-primary" title="Generar"> <span class="fa fa-edit" ></span></a>
										<?php 
											}
										?>
										<?php
										if($permisos->rop_reporte && $factura->reg_estado==22){
										?>
												<a href="<?php echo base_url();?>cruce_cuentas/show_frame/<?php echo $factura->reg_num_documento?>/<?php echo $permisos->opc_id?>" class="btn btn-warning" title="Detalle"> <span class="fa fa-file-pdf-o" ></span></a>
										<?php 
											}
										?>
									</div>
								</td>
							</tr>
						<?php
								$grup=$factura->cli_ced_ruc;
								$t_credito+=round($factura->reg_total,$dec);
								$t_debito+=round($factura->pago,$dec);
								$t_saldo+=round($saldo,$dec);
							}
						}
						}
						?>
								<tr class='success'>
										<td class='total' colspan="5" style='font-weight: bolder;'></td>
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


