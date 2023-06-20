<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>rep_declaracion/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel3()"  >
		<input type="submit" value="EXCEL" class="btn btn-success" />
		<input type="hidden" id="datatodisplay" name="datatodisplay">
	</form>
	<h1>
		Declaracion <?php echo $titulo?>
	</h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">

			<div class="row">

				<div class="col-md-8">
					<form action="<?php echo $buscar;?>" method="post">

						<table width="100%">
							<tr>
								<td><label>Buscar:</label></td>
								<td><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>' placeholder="DOCUMENTO/CLIENTE/CEDULA"/></td>
								<td>
								</td>
								<td><label>Desde:</label></td>
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
					<table class="table tabla table-bordered table-list table-hover" id="tabla1">
						<thead id="thead1">
							<tr>
								<th colspan="26" style="text-align: center;">Ventas</th>
							</tr>
							<tr>
								<th colspan="10" style="text-align: center;">Factura</th>
								<th colspan="9" style="text-align: center;">Nota Credito</th>
								<th colspan="7" style="text-align: center;">Retencion</th>
							</tr>
							<tr>	
								<th>Fecha</th>
								<th>#Doc</th>
								<th>Cliente</th>
								<th>Ruc/CI</th>
								<th>Subtotal</th>
								<th>Descuento</th>
								<th>Subt0%</th>
								<th>Subt12%</th>
								<th>Iva</th>
								<th>Total</th>
								<th>Fecha</th>
								<th>#Doc</th>
								<th>Subtotal</th>
								<th>Descuento</th>
								<th>Subt0%</th>
								<th>Subt12%</th>
								<th>Iva</th>
								<th>Total</th>
								<th>Factura-NC</th>
								<th>Fecha</th>
								<th>#Doc</th>
								<th>Iva%</th>
								<th>Iva$</th>
								<th>Renta%</th>
								<th>Renta$</th>
								<th>Valor</th>
							</tr>

						</thead>
						<tbody>
							<?php 
							$n=0;
							$sub0=0;
							$grup=0;
							$fsubt=0;
							$fdesc=0;
							$fiva0=0;
							$fiva12=0;
							$tiva=0;
							$ftotal=0;
							$ncsubt=0;
							$ncdesc=0;
							$nciva0=0;
							$nciva12=0;
							$nciva=0;
							$nctotal=0;
							$fcnctotal=0;
							$retiva=0;
							$retrent=0;
							$retval=0;
							if(!empty($ventas)){
								foreach ($ventas as $vnt) {
									$n++;

									$sub0=round($vnt->fac_subtotal0,$dec)+round($vnt->fac_subtotal_ex_iva,$dec)+round($vnt->fac_subtotal_no_iva,$dec);
									$subn0=round($vnt->ncr_subtotal0,$dec)+round($vnt->ncr_subtotal_ex_iva,$dec)+round($vnt->ncr_subtotal_no_iva,$dec);
									$fn=round($vnt->fac_total_valor,$dec)-round($vnt->nrc_total_valor,$dec);

									?>
									<tr>
										<?php
										if($grup!=$vnt->fac_id){	
											?>
											<td><?php echo $vnt->fac_fecha_emision?></td>
											<td><?php echo $vnt->fac_numero?></td>
											<td><?php echo $vnt->fac_nombre?></td>
											<td style="mso-number-format:'@'"><?php echo $vnt->fac_identificacion?></td>
											<td style="text-align: right;"><?php echo number_format($vnt->fac_subtotal,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($vnt->fac_total_descuento,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($sub0,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($vnt->fac_subtotal12,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($vnt->fac_total_iva,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($vnt->fac_total_valor,$dec)?></td>
											<?php
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
										<td><?php echo $vnt->ncr_fecha_emision?></td>
										<td><?php echo $vnt->ncr_numero?></td>
										<td style="text-align: right;"><?php echo number_format($vnt->ncr_subtotal,$dec)?></td>
										<td style="text-align: right;"><?php echo number_format($vnt->ncr_total_descuento,$dec)?></td>
										<td style="text-align: right;"><?php echo number_format($subn0,$dec)?></td>
										<td style="text-align: right;"><?php echo number_format($vnt->ncr_subtotal12,$dec)?></td>
										<td style="text-align: right;"><?php echo number_format($vnt->ncr_total_iva,$dec)?></td>
										<td style="text-align: right;"><?php echo number_format($vnt->nrc_total_valor,$dec)?></td>
										<td style="text-align: right;"><?php echo number_format($fn,$dec)?></td>
										<?php
										if($grup!=$vnt->fac_id){	
											?>
											<td><?php echo $vnt->rgr_fecha_emision?></td>
											<td><?php echo $vnt->rgr_numero?></td>
											<td style="text-align: right;"><?php echo $vnt->por_iva?></td>
											<td style="text-align: right;"><?php echo number_format($vnt->valor_iva,$dec)?></td>
											<td style="text-align: right;"><?php echo $vnt->por_renta?></td>
											<td style="text-align: right;"><?php echo number_format($vnt->valor_renta,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($vnt->rgr_total_valor,$dec)?></td>
											<?php
										}else{	
											?>
											<td></td>
											<td></td>
											<td></td>
											<td style="text-align: right;"><?php echo number_format(0,$dec)?></td>
											<td></td>
											<td style="text-align: right;"><?php echo number_format(0,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format(0,$dec)?></td>
											<?php
										}	
										?>
									</tr>
									<?php
									$grup=$vnt->fac_id;
									$fsubt+=round($vnt->fac_subtotal,$dec);
									$fdesc+=round($vnt->fac_total_descuento,$dec);
									$fiva0+=round($sub0,$dec);
									$fiva12+=round($vnt->fac_subtotal12,$dec);
									$tiva+=round($vnt->fac_total_iva,$dec);
									$ftotal+=round($vnt->fac_total_valor,$dec);
									$ncsubt+=round($vnt->ncr_subtotal,$dec);
									$ncdesc+=round($vnt->ncr_total_descuento,$dec);
									$nciva0+=round($subn0,$dec);
									$nciva12+=round($vnt->ncr_subtotal12,$dec);
									$nciva+=round($vnt->ncr_total_iva,$dec);
									$nctotal+=round($vnt->nrc_total_valor,$dec);
									$fcnctotal+=round($fn,$dec);
									$retiva+=round($vnt->valor_iva,$dec);
									$retrent+=round($vnt->valor_renta,$dec);
									$retval+=round($vnt->rgr_total_valor,$dec);
								}
							}
							?>

							<tr class='success'>
								<td class="total"></td>
								<td class="total"></td>
								<td class="total"></td>
								<td class="total" align='right'>Total</td>
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($fsubt,$dec) ?> </td>
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($fdesc,$dec) ?> </td>    
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($fiva0,$dec) ?> </td>
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($fiva12,$dec) ?> </td>    
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($tiva,$dec) ?> </td>                        
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($ftotal,$dec) ?> </td>
								<td class="total" align='right' style='font-size:14px;'></td>    
								<td class="total" align='right' style='font-size:14px;'></td>    
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($ncsubt,$dec) ?> </td>
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($ncdesc,$dec) ?> </td>    
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nciva0,$dec) ?> </td>
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nciva12,$dec) ?> </td>
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nciva,$dec) ?> </td>    
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nctotal,$dec) ?> </td>
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($fcnctotal,$dec) ?> </td>    
								<td class="total" align='right' style='font-size:14px;'></td>
								<td class="total" align='right' style='font-size:14px;'></td>
								<td class="total" align='right' style='font-size:14px;'></td>  
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($retiva,$dec) ?> </td>
								<td class="total" align='right' style='font-size:14px;'></td>  
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($retrent,$dec) ?> </td>
								<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($retval,$dec) ?> </td>    
							</tr>
						</tbody>
					</table>
				</div>	
				<div class="row">
					<div class="col-md-12">
						<table class="table tabla table-bordered table-list table-hover" id="tabla2">
							<thead id="thead2">
								<tr>
									<th colspan="26" style="text-align: center;">Notas de Credito de Ventas sin Facturas o con Facturas de Otro Periodo</th>
								</tr>
								<tr>
									<th colspan="10" style="text-align: center;">Factura</th>
									<th colspan="9" style="text-align: center;">Nota Credito</th>
									<th colspan="7" style="text-align: center;">Retencion</th>
								</tr>
								<tr>	
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Cliente</th>
									<th>Ruc/CI</th>
									<th>Subtotal</th>
									<th>Descuento</th>
									<th>Subt0%</th>
									<th>Subt12%</th>
									<th>Iva</th>
									<th>Total</th>
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Subtotal</th>
									<th>Descuento</th>
									<th>Subt0%</th>
									<th>Subt12%</th>
									<th>Iva</th>
									<th>Total</th>
									<th>Factura-NC</th>
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Iva%</th>
									<th>Iva$</th>
									<th>Renta%</th>
									<th>Renta$</th>
									<th>Valor</th>
								</tr>
							</thead>
							<tbody>	

								<?php 
								$m=0;
								$subn0=0;
								$n_fsubt=0;
								$n_fdesc=0;
								$n_fiva0=0;
								$n_fiva12=0;
								$n_tiva=0;
								$n_ftotal=0;
								$n_ncsubt=0;
								$n_ncdesc=0;
								$n_nciva0=0;
								$n_nciva12=0;
								$n_nciva=0;
								$n_nctotal=0;
								$n_fcnctotal=0;
								$n_retiva=0;
								$n_retrent=0;
								$n_retval=0;
								if(!empty($notas)){
									foreach ($notas as $not) {
										$m++;

										$subn0=round($not->fac_subtotal0,$dec)+round($not->fac_subtotal_ex_iva,$dec)+round($not->fac_subtotal_no_iva,$dec);
										$subnn0=round($not->ncr_subtotal0,$dec)+round($not->ncr_subtotal_ex_iva,$dec)+round($not->ncr_subtotal_no_iva,$dec);
										$fnn=round($not->fac_total_valor,$dec)-round($not->nrc_total_valor,$dec);

										?>
										<tr>

											<td><?php echo $not->fac_fecha_emision?></td>
											<td><?php echo $not->fac_numero?></td>
											<td><?php echo $not->fac_nombre?></td>
											<td style="mso-number-format:'@'"><?php echo $not->fac_identificacion?></td>
											<td style="text-align: right;"><?php echo number_format($not->fac_subtotal,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($not->fac_total_descuento,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($subn0,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($not->fac_subtotal12,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($not->fac_total_iva,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($not->fac_total_valor,$dec)?></td>
											<td><?php echo $not->ncr_fecha_emision?></td>
											<td><?php echo $not->ncr_numero?></td>
											<td style="text-align: right;"><?php echo number_format($not->ncr_subtotal,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($not->ncr_total_descuento,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($subnn0,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($not->ncr_subtotal12,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($not->ncr_total_iva,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($not->nrc_total_valor,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($fnn,$dec)?></td>

											<td><?php echo $not->rgr_fecha_emision?></td>
											<td><?php echo $not->rgr_numero?></td>
											<td style="text-align: right;"><?php echo $not->por_iva?></td>
											<td style="text-align: right;"><?php echo number_format($not->valor_iva,$dec)?></td>
											<td style="text-align: right;"><?php echo $not->por_renta?></td>
											<td style="text-align: right;"><?php echo number_format($not->valor_renta,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($not->rgr_total_valor,$dec)?></td>


										</tr>
										<?php
										$n_fsubt+=round($not->fac_subtotal,$dec);
										$n_fdesc+=round($not->fac_total_descuento,$dec);
										$n_fiva0+=round($sub0,$dec);
										$n_fiva12+=round($not->fac_subtotal12,$dec);
										$n_tiva+=round($not->fac_total_iva,$dec);
										$n_ftotal+=round($not->fac_total_valor,$dec);
										$n_ncsubt+=round($not->ncr_subtotal,$dec);
										$n_ncdesc+=round($not->ncr_total_descuento,$dec);
										$n_nciva0+=round($subn0,$dec);
										$n_nciva12+=round($not->ncr_subtotal12,$dec);
										$n_nciva+=round($not->ncr_total_iva,$dec);
										$n_nctotal+=round($not->nrc_total_valor,$dec);
										$n_fcnctotal+=round($fnn,$dec);
										$n_retiva+=round($not->valor_iva,$dec);
										$n_retrent+=round($not->valor_renta,$dec);
										$n_retval+=round($not->rgr_total_valor,$dec);
									}
								}
								?>
								<tr>
									<td class="total"></td>
									<td class="total"></td>
									<td class="total"></td>
									<td class="total" align='right'>Total</td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_fsubt,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_fdesc,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_fiva0,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_fiva12,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_tiva,$dec) ?> </td>                        
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_ftotal,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'></td>    
									<td class="total" align='right' style='font-size:14px;'></td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_ncsubt,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_ncdesc,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_nciva0,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_nciva12,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_nciva,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_nctotal,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_fcnctotal,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'></td>
									<td class="total" align='right' style='font-size:14px;'></td>
									<td class="total" align='right' style='font-size:14px;'></td>  
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_retiva,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'></td>  
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_retrent,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($n_retval,$dec) ?> </td>    
								</tr>

							</tbody>
						</table>
					</div>	
				</div>
				<div class="row">
					<div class="col-md-12">
						<table class="table tabla table-bordered table-list table-hover" id="tabla3">
							<thead id="thead3">
								<tr>
									<th colspan="26" style="text-align: center;">Retenciones de Ventas con Facturas de Otro Periodo</th>
								</tr>
								<tr>
									<th colspan="10" style="text-align: center;">Factura</th>
									<th colspan="9" style="text-align: center;">Nota Credito</th>
									<th colspan="7" style="text-align: center;">Retencion</th>
								</tr>
								<tr>	
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Cliente</th>
									<th>Ruc/CI</th>
									<th>Subtotal</th>
									<th>Descuento</th>
									<th>Subt0%</th>
									<th>Subt12%</th>
									<th>Iva</th>
									<th>Total</th>
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Subtotal</th>
									<th>Descuento</th>
									<th>Subt0%</th>
									<th>Subt12%</th>
									<th>Iva</th>
									<th>Total</th>
									<th>Factura-NC</th>
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Iva%</th>
									<th>Iva$</th>
									<th>Renta%</th>
									<th>Renta$</th>
									<th>Valor</th>
								</tr>
							</thead>
							<tbody>	

								<?php 
								$m=0;
								$subr0=0;
								$r_fsubt=0;
								$r_fdesc=0;
								$r_fiva0=0;
								$r_fiva12=0;
								$r_tiva=0;
								$r_ftotal=0;
								$r_ncsubt=0;
								$r_ncdesc=0;
								$r_nciva0=0;
								$r_nciva12=0;
								$r_nciva=0;
								$r_nctotal=0;
								$r_fcnctotal=0;
								$r_retiva=0;
								$r_retrent=0;
								$r_retval=0;
								if(!empty($retenciones)){
									foreach ($retenciones as $ret) {
										$m++;

										$subr0=round($ret->fac_subtotal0,$dec)+round($ret->fac_subtotal_ex_iva,$dec)+round($ret->fac_subtotal_no_iva,$dec);
										?>
										<tr>

											<td><?php echo $ret->fac_fecha_emision?></td>
											<td><?php echo $ret->fac_numero?></td>
											<td><?php echo $ret->fac_nombre?></td>
											<td style="mso-number-format:'@'"><?php echo $ret->fac_identificacion?></td>
											<td style="text-align: right;"><?php echo number_format($ret->fac_subtotal,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($ret->fac_total_descuento,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($subr0,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($ret->fac_subtotal12,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($ret->fac_total_iva,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($ret->fac_total_valor,$dec)?></td>
											<td></td>
											<td></td>
											<td style="text-align: right;"></td>
											<td style="text-align: right;"></td>
											<td style="text-align: right;"></td>
											<td style="text-align: right;"></td>
											<td style="text-align: right;"></td>
											<td style="text-align: right;"></td>
											<td style="text-align: right;"><?php echo number_format($ret->fac_total_valor,$dec)?></td>

											<td><?php echo $ret->rgr_fecha_emision?></td>
											<td><?php echo $ret->rgr_numero?></td>
											<td style="text-align: right;"><?php echo $ret->por_iva?></td>
											<td style="text-align: right;"><?php echo number_format($ret->valor_iva,$dec)?></td>
											<td style="text-align: right;"><?php echo $ret->por_renta?></td>
											<td style="text-align: right;"><?php echo number_format($ret->valor_renta,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($ret->rgr_total_valor,$dec)?></td>


										</tr>
										<?php
										$r_fsubt+=round($ret->fac_subtotal,$dec);
										$r_fdesc+=round($ret->fac_total_descuento,$dec);
										$r_fiva0+=round($subr0,$dec);
										$r_fiva12+=round($ret->fac_subtotal12,$dec);
										$r_tiva+=round($ret->fac_total_iva,$dec);
										$r_ftotal+=round($ret->fac_total_valor,$dec);
										$r_fcnctotal+=round($ret->fac_total_valor,$dec);
										$r_retiva+=round($ret->valor_iva,$dec);
										$r_retrent+=round($ret->valor_renta,$dec);
										$r_retval+=round($ret->rgr_total_valor,$dec);
									}
								}
								?>
								<tr>
									<td class="total"></td>
									<td class="total"></td>
									<td class="total"></td>
									<td class="total" align='right'>Total</td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_fsubt,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_fdesc,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_fiva0,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_fiva12,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_tiva,$dec) ?> </td>                        
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_ftotal,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'></td>    
									<td class="total" align='right' style='font-size:14px;'></td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_ncsubt,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_ncdesc,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_nciva0,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_nciva12,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_nciva,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_nctotal,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_fcnctotal,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'></td>
									<td class="total" align='right' style='font-size:14px;'></td>
									<td class="total" align='right' style='font-size:14px;'></td>  
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_retiva,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'></td>  
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_retrent,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($r_retval,$dec) ?> </td>    
								</tr>
							</tbody>
						</table>
					</div>	
				</div>

				<div class="row">
					<div class="col-md-12">
						<table class="table tabla table-bordered table-list table-hover" id="tabla4">
							<thead id="thead4">
								<tr>
									<th colspan="26" style="text-align: center;">Compras</th>
								</tr>
								<tr>
									<th colspan="10" style="text-align: center;">Factura</th>
									<th colspan="9" style="text-align: center;">Nota Credito</th>
									<th colspan="7" style="text-align: center;">Retencion</th>
								</tr>
								<tr>	
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Cliente</th>
									<th>Ruc/CI</th>
									<th>Subtotal</th>
									<th>Descuento</th>
									<th>Subt0%</th>
									<th>Subt12%</th>
									<th>Iva</th>
									<th>Total</th>
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Subtotal</th>
									<th>Descuento</th>
									<th>Subt0%</th>
									<th>Subt12%</th>
									<th>Iva</th>
									<th>Total</th>
									<th>Factura-NC</th>
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Iva%</th>
									<th>Iva$</th>
									<th>Renta%</th>
									<th>Renta$</th>
									<th>Valor</th>
								</tr>
							</thead>
							<tbody>	

								<?php 
								$m=0;
								$subn0=0;
								$c_fsubt=0;
								$c_fdesc=0;
								$c_fiva0=0;
								$c_fiva12=0;
								$c_tiva=0;
								$c_ftotal=0;
								$c_ncsubt=0;
								$c_ncdesc=0;
								$c_nciva0=0;
								$c_nciva12=0;
								$c_nciva=0;
								$c_nctotal=0;
								$c_fcnctotal=0;
								$c_retiva=0;
								$c_retrent=0;
								$c_retval=0;
								if(!empty($compras)){
									foreach ($compras as $comp) {
										$m++;

										$subc0=round($comp->reg_sbt0,$dec)+round($comp->reg_sbt_noiva,$dec)+round($comp->reg_sbt_excento,$dec);

										$subcnn0=round($comp->rnc_subtotal0,$dec)+round($comp->rnc_subtotal_ex_iva,$dec)+round($comp->rnc_subtotal_no_iva,$dec);

										$cnn=round($comp->reg_total,$dec)-round($comp->rnc_total_valor,$dec);
										?>
										<tr>

											<td><?php echo $comp->reg_femision?></td>
											<td><?php echo $comp->reg_num_documento?></td>
											<td><?php echo $comp->cli_raz_social?></td>
											<td style="mso-number-format:'@'"><?php echo $comp->cli_ced_ruc?></td>
											<td style="text-align: right;"><?php echo number_format($comp->reg_sbt,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($comp->reg_tdescuento,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($subc0,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($comp->reg_sbt12,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($comp->reg_iva12,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($comp->reg_total,$dec)?></td>
											<td><?php echo $comp->rnc_fecha_emision?></td>
											<td><?php echo $comp->rnc_numero?></td>
											<td style="text-align: right;"><?php echo number_format($comp->rnc_subtotal,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($comp->rnc_total_descuento,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($subcnn0,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($comp->rnc_subtotal12,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($comp->rnc_total_iva,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($comp->rnc_total_valor,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($cnn,$dec)?></td>

											<td><?php echo $comp->ret_fecha_emision?></td>
											<td><?php echo $comp->ret_numero?></td>
											<td style="text-align: right;"><?php echo $comp->por_iva?></td>
											<td style="text-align: right;"><?php echo number_format($comp->valor_iva,$dec)?></td>
											<td style="text-align: right;"><?php echo $comp->por_renta?></td>
											<td style="text-align: right;"><?php echo number_format($comp->valor_renta,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($comp->ret_total_valor,$dec)?></td>


										</tr>

										<?php
										$c_fsubt+= round($comp->reg_sbt,$dec);
										$c_fdesc+= round($comp->reg_tdescuento,$dec);
										$c_fiva0+= round($subc0,$dec);
										$c_fiva12+= round($comp->reg_sbt12,$dec);
										$c_tiva+= round($comp->reg_iva12,$dec);
										$c_ftotal+= round($comp->reg_total,$dec);
										$c_ncsubt+= round($comp->rnc_subtotal,$dec);
										$c_ncdesc+= round($comp->rnc_total_descuento,$dec);
										$c_nciva0+= round($subcnn0,$dec);
										$c_nciva12+= round($comp->rnc_subtotal12,$dec);
										$c_nciva+= round($comp->rnc_total_iva,$dec);
										$c_nctotal+= round($comp->rnc_total_valor,$dec);
										$c_fcnctotal+= round($cnn,$dec);
										$c_retiva+= round($comp->valor_iva,$dec);
										$c_retrent+= round($comp->valor_renta,$dec);
										$c_retval+= round($comp->ret_total_valor,$dec);
									}
								}
								?>
								<tr>
									<td class="total"></td>
									<td class="total"></td>
									<td class="total"></td>
									<td class="total" align='right'>Total</td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_fsubt,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_fdesc,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_fiva0,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_fiva12,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_tiva,$dec) ?> </td>                        
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_ftotal,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'></td>    
									<td class="total" align='right' style='font-size:14px;'></td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_ncsubt,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_ncdesc,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_nciva0,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_nciva12,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_nciva,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_nctotal,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_fcnctotal,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'></td>
									<td class="total" align='right' style='font-size:14px;'></td>
									<td class="total" align='right' style='font-size:14px;'></td>  
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_retiva,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'></td>  
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_retrent,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($c_retval,$dec) ?> </td>    
								</tr>
							</tbody>
						</table>
					</div>	
				</div>

				<div class="row">
					<div class="col-md-12">
						<table class="table tabla table-bordered table-list table-hover" id="tabla5">
							<thead id="thead5">
								<tr>
									<th colspan="26" style="text-align: center;">Notas de Cedito Compras con Facturas de Otro Periodo</th>
								</tr>
								<tr>
									<th colspan="10" style="text-align: center;">Factura</th>
									<th colspan="9" style="text-align: center;">Nota Credito</th>
									<th colspan="7" style="text-align: center;">Retencion</th>
								</tr>
								<tr>	
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Cliente</th>
									<th>Ruc/CI</th>
									<th>Subtotal</th>
									<th>Descuento</th>
									<th>Subt0%</th>
									<th>Subt12%</th>
									<th>Iva</th>
									<th>Total</th>
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Subtotal</th>
									<th>Descuento</th>
									<th>Subt0%</th>
									<th>Subt12%</th>
									<th>Iva</th>
									<th>Total</th>
									<th>Factura-NC</th>
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Iva%</th>
									<th>Iva$</th>
									<th>Renta%</th>
									<th>Renta$</th>
									<th>Valor</th>
								</tr>
							</thead>
							<tbody>	

								<?php 
								$m=0;
								$subc0=0;
								$nc_fsubt=0;
								$nc_fdesc=0;
								$nc_fiva0=0;
								$nc_fiva12=0;
								$nc_tiva=0;
								$nc_ftotal=0;
								$nc_ncsubt=0;
								$nc_ncdesc=0;
								$nc_nciva0=0;
								$nc_nciva12=0;
								$nc_nciva=0;
								$nc_nctotal=0;
								$nc_fcnctotal=0;
								$nc_retiva=0;
								$nc_retrent=0;
								$nc_retval=0;
								if(!empty($notas_compras)){
									foreach ($notas_compras as $ntcom) {
										$m++;

										$subc0=round($ntcom->reg_sbt0,$dec)+round($ntcom->reg_sbt_noiva,$dec)+round($ntcom->reg_sbt_excento,$dec);

										$subcnn0=round($ntcom->rnc_subtotal0,$dec)+round($ntcom->rnc_subtotal_ex_iva,$dec)+round($ntcom->rnc_subtotal_no_iva,$dec);

										$tcn=round($ntcom->reg_total,$dec)-round($ntcom->rnc_total_valor,$dec);
										?>
										<tr>

											<td><?php echo $ntcom->reg_femision?></td>
											<td><?php echo $ntcom->reg_num_documento?></td>
											<td><?php echo $ntcom->cli_raz_social?></td>
											<td style="mso-number-format:'@'"><?php echo $ntcom->cli_ced_ruc?></td>
											<td style="text-align: right;"><?php echo number_format($ntcom->reg_sbt,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($ntcom->reg_tdescuento,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($subc0,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($ntcom->reg_sbt12,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($ntcom->reg_iva12,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($ntcom->reg_total,$dec)?></td>
											<td><?php echo $ntcom->rnc_fecha_emision?></td>
											<td><?php echo $ntcom->rnc_numero?></td>
											<td style="text-align: right;"><?php echo number_format($ntcom->rnc_subtotal,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($ntcom->rnc_total_descuento,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($subcnn0,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($ntcom->rnc_subtotal12,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($ntcom->rnc_total_iva,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($ntcom->rnc_total_valor,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($tcn,$dec)?></td>

											<td><?php echo $ntcom->ret_fecha_emision?></td>
											<td><?php echo $ntcom->ret_numero?></td>
											<td style="text-align: right;"><?php echo $ntcom->por_iva?></td>
											<td style="text-align: right;"><?php echo number_format($ntcom->valor_iva,$dec)?></td>
											<td style="text-align: right;"><?php echo $ntcom->por_renta?></td>
											<td style="text-align: right;"><?php echo number_format($ntcom->valor_renta,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($ntcom->ret_total_valor,$dec)?></td>
										</tr>
										<?php
										$nc_fsubt+= round($ntcom->reg_sbt,$dec);
										$nc_fdesc+= round($ntcom->reg_tdescuento,$dec);
										$nc_fiva0+= round($subc0,$dec);
										$nc_fiva12+= round($ntcom->reg_sbt12,$dec);
										$nc_tiva+= round($ntcom->reg_iva12,$dec);
										$nc_ftotal+= round($ntcom->reg_total,$dec);
										$nc_ncsubt+= round($ntcom->rnc_subtotal,$dec);
										$nc_ncdesc+= round($ntcom->rnc_total_descuento,$dec);
										$nc_nciva0+= round($subcnn0,$dec);
										$nc_nciva12+= round($ntcom->rnc_subtotal12,$dec);
										$nc_nciva+= round($ntcom->rnc_total_iva,$dec);
										$nc_nctotal+= round($ntcom->rnc_total_valor,$dec);
										$nc_fcnctotal+= round($tcn,$dec);
										$nc_retiva+= round($ntcom->valor_iva,$dec);
										$nc_retrent+= round($ntcom->valor_renta,$dec);
										$nc_retval+= round($ntcom->ret_total_valor,$dec);
									}
								}
								?>
								<tr>
									<td class="total"></td>
									<td class="total"></td>
									<td class="total"></td>
									<td class="total" align='right'>Total</td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_fsubt,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_fdesc,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_fiva0,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_fiva12,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_tiva,$dec) ?> </td>                        
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_ftotal,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'></td>    
									<td class="total" align='right' style='font-size:14px;'></td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_ncsubt,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_ncdesc,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_nciva0,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_nciva12,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_nciva,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_nctotal,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_fcnctotal,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'></td>
									<td class="total" align='right' style='font-size:14px;'></td>
									<td class="total" align='right' style='font-size:14px;'></td>  
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_retiva,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'></td>  
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_retrent,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($nc_retval,$dec) ?> </td>    
								</tr>
							</tbody>
						</table>
					</div>	
				</div>

				<div class="row">
					<div class="col-md-12">
						<table class="table tabla table-bordered table-list table-hover" id="tabla6">
							<thead id="thead6">
								<tr>
									<th colspan="26" style="text-align: center;">Retenciones de Compras con Facturas de Otro Periodo</th>
								</tr>
								<tr>
									<th colspan="10" style="text-align: center;">Factura</th>
									<th colspan="9" style="text-align: center;">Nota Credito</th>
									<th colspan="7" style="text-align: center;">Retencion</th>
								</tr>
								<tr>	
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Cliente</th>
									<th>Ruc/CI</th>
									<th>Subtotal</th>
									<th>Descuento</th>
									<th>Subt0%</th>
									<th>Subt12%</th>
									<th>Iva</th>
									<th>Total</th>
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Subtotal</th>
									<th>Descuento</th>
									<th>Subt0%</th>
									<th>Subt12%</th>
									<th>Iva</th>
									<th>Total</th>
									<th>Factura-NC</th>
									<th>Fecha</th>
									<th>#Doc</th>
									<th>Iva%</th>
									<th>Iva$</th>
									<th>Renta%</th>
									<th>Renta$</th>
									<th>Valor</th>
								</tr>
							</thead>
							<tbody>	

								<?php 
								$m=0;
								$subn0=0;
								$rc_fsubt=0;
								$rc_fdesc=0;
								$rc_fiva0=0;
								$rc_fiva12=0;
								$rc_tiva=0;
								$rc_ftotal=0;
								$rc_ncsubt=0;
								$rc_ncdesc=0;
								$rc_nciva0=0;
								$rc_nciva12=0;
								$rc_nciva=0;
								$rc_nctotal=0;
								$rc_fcnctotal=0;
								$rc_retiva=0;
								$rc_retrent=0;
								$rc_retval=0;
								if(!empty($retenciones_compras)){
									foreach ($retenciones_compras as $rtcom) {
										$m++;

										$subc0=round($rtcom->reg_sbt0,$dec)+round($rtcom->reg_sbt_noiva,$dec)+round($rtcom->reg_sbt_excento,$dec);

										$subcnn0=round($rtcom->rnc_subtotal0,$dec)+round($rtcom->rnc_subtotal_ex_iva,$dec)+round($rtcom->rnc_subtotal_no_iva,$dec);

										$tcn=round($rtcom->reg_total,$dec)-round($rtcom->rnc_total_valor,$dec);
										?>
										<tr>

											<td><?php echo $rtcom->reg_femision?></td>
											<td><?php echo $rtcom->reg_num_documento?></td>
											<td><?php echo $rtcom->cli_raz_social?></td>
											<td style="mso-number-format:'@'"><?php echo $rtcom->cli_ced_ruc?></td>
											<td style="text-align: right;"><?php echo number_format($rtcom->reg_sbt,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($rtcom->reg_tdescuento,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($subc0,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($rtcom->reg_sbt12,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($rtcom->reg_iva12,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($rtcom->reg_total,$dec)?></td>
											<td><?php echo $rtcom->rnc_fecha_emision?></td>
											<td><?php echo $rtcom->rnc_numero?></td>
											<td style="text-align: right;"><?php echo number_format($rtcom->rnc_subtotal,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($rtcom->rnc_total_descuento,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($subcnn0,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($rtcom->rnc_subtotal12,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($rtcom->rnc_total_iva,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($rtcom->rnc_total_valor,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($tcn,$dec)?></td>

											<td><?php echo $rtcom->ret_fecha_emision?></td>
											<td><?php echo $rtcom->ret_numero?></td>
											<td style="text-align: right;"><?php echo $rtcom->por_iva?></td>
											<td style="text-align: right;"><?php echo number_format($rtcom->valor_iva,$dec)?></td>
											<td style="text-align: right;"><?php echo $rtcom->por_renta?></td>
											<td style="text-align: right;"><?php echo number_format($rtcom->valor_renta,$dec)?></td>
											<td style="text-align: right;"><?php echo number_format($rtcom->ret_total_valor,$dec)?></td>
										</tr>
										<?php
										$rc_fsubt+= round($rtcom->reg_sbt,$dec);
										$rc_fdesc+= round($rtcom->reg_tdescuento,$dec);
										$rc_fiva0+= round($subc0,$dec);
										$rc_fiva12+= round($rtcom->reg_sbt12,$dec);
										$rc_tiva+= round($rtcom->reg_iva12,$dec);
										$rc_ftotal+= round($rtcom->reg_total,$dec);
										$rc_ncsubt+= round($rtcom->rnc_subtotal,$dec);
										$rc_ncdesc+= round($rtcom->rnc_total_descuento,$dec);
										$rc_nciva0+= round($subcnn0,$dec);
										$rc_nciva12+= round($rtcom->rnc_subtotal12,$dec);
										$rc_nciva+= round($rtcom->rnc_total_iva,$dec);
										$rc_nctotal+= round($rtcom->rnc_total_valor,$dec);
										$rc_fcnctotal+= round($tcn,$dec);
										$rc_retiva+= round($rtcom->valor_iva,$dec);
										$rc_retrent+= round($rtcom->valor_renta,$dec);
										$rc_retval+= round($rtcom->ret_total_valor,$dec);
									}
								}
								?>
								<tr>
									<td class="total"></td>
									<td class="total"></td>
									<td class="total"></td>
									<td class="total" align='right'>Total</td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_fsubt,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_fdesc,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_fiva0,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_fiva12,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_tiva,$dec) ?> </td>                        
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_ftotal,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'></td>    
									<td class="total" align='right' style='font-size:14px;'></td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_ncsubt,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_ncdesc,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_nciva0,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_nciva12,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_nciva,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_nctotal,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_fcnctotal,$dec) ?> </td>    
									<td class="total" align='right' style='font-size:14px;'></td>
									<td class="total" align='right' style='font-size:14px;'></td>
									<td class="total" align='right' style='font-size:14px;'></td>  
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_retiva,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'></td>  
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_retrent,$dec) ?> </td>
									<td class="total" align='right' style='font-size:14px;'> <?php echo number_format($rc_retval,$dec) ?> </td>    
								</tr>
							</tbody>
						</table>
					</div>	
				</div>

				<div class="row ">
					<table id="tabla11">
						<tr>
							<td style="float: top;">
								<div class="col-md-3">
									<table class="table tabla table-bordered table-list table-hover" id="tabla7">
										<thead id="thead7">
											<tr>
												<th colspan="2">Total Ventas</th>
											</tr>
											<tr>
												<th colspan="2">Facturas</th>
											</tr>
										</thead>
										<tr>
											<td>Subtotal</td>
											<td align='right' style='font-size:14px;'> <?php echo number_format($fsubt, $dec) ?> </td>

										</tr>   
										<tr>    
											<td>Descuento</td>
											<td align='right' style='font-size:14px;'> <?php echo number_format($fdesc, $dec) ?> </td>    
										</tr>   
										<tr>    
											<td>Subtotal 0%</td>
											<td align='right' style='font-size:14px;'> <?php echo number_format($fiva0, $dec) ?> </td>
										</tr>   
										<tr>    
											<td>Subtotal 12%</td>
											<td align='right' style='font-size:14px;'> <?php echo number_format($fiva12, $dec) ?> </td>    
										</tr>   
										<tr>    
											<td>IVA</td>
											<td align='right' style='font-size:14px;'> <?php echo number_format($tiva, $dec) ?> </td>                        
										</tr>   
										<tr>
											<td>Total $</td>
											<td align='right' style='font-size:14px;'> <?php echo number_format($ftotal, $dec) ?> </td>
										</tr>
										<thead>
											<tr>
												<th colspan="2">Notas de Credito</td>
												</tr>
											</thead>    
											<tr>
												<td>Subtotal</td>
												<td align='right' style='font-size:14px;'> <?php echo number_format($ncsubt+$n_ncsubt, $dec) ?> </td>

											</tr>
											<tr>    
												<td>Descuento</td>
												<td align='right' style='font-size:14px;'> <?php echo number_format($ncdesc+$n_ncdesc, $dec) ?> </td>    
											</tr>
											<tr>    
												<td>Subtotal 0%</td>
												<td align='right' style='font-size:14px;'> <?php echo number_format($nciva0+$n_nciva0, $dec) ?> </td>
											</tr>
											<tr>    
												<td>Subtotal 12%</td>
												<td align='right' style='font-size:14px;'> <?php echo number_format($nciva12+$n_nciva12, $dec) ?> </td>
											</tr>
											<tr>    
												<td>IVA</td>
												<td align='right' style='font-size:14px;'> <?php echo number_format($nciva+$n_nciva, $dec) ?> </td>    
											</tr>
											<tr>    
												<td>Total $</td>
												<td align='right' style='font-size:14px;'> <?php echo number_format($nctotal+$n_nctotal, $dec) ?> </td>
											</tr>
											<thead>   
												<tr>
													<th colspan="2">Retencion</td>
													</tr>
												</thead>
												<?php 
												$t_retiva=round($retiva, $dec)+round($r_retiva, $dec);
												$t_retrent=round($retrent, $dec)+round($r_retrent, $dec);
												$t_retval=round($t_retiva+$t_retrent, $dec);
												?>    
												<tr>  
													<td>IVA</td>
													<td align='right' style='font-size:14px;'> <?php echo number_format($t_retiva, $dec) ?> </td>    
												</tr>
												<tr>
													<td>Renta</td>
													<td align='right' style='font-size:14px;'> <?php echo number_format($t_retrent, $dec) ?> </td>
												</tr>
												<tr>
													<td>Valor</td> 
													<td align='right' style='font-size:14px;'> <?php echo number_format($t_retval, $dec) ?></td>    
												</tr>
											</table>
										</div>
									</td>	
									<td valign="top">
										<div class="col-md-3">
											<table class="table tabla table-bordered table-list table-hover" id="tabla8">
												<thead id="thead8">
													<tr>
														<th colspan="3">Retenciones Ventas</th>
													</tr>
													<tr>
														<th>Impuesto</th>
														<th>Porcentaje</th>
														<th>Valor</th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach ($cns_p as $rst_p) {
														if($rst_p->drr_tipo_impuesto=='IV'){
															$imp='IVA';
														}else if($rst_p->drr_tipo_impuesto=='IR'){
															$imp='RENTA';
														}
														?>
														<tr>
															<td>
																<?php echo $imp ?> 
															</td>
															<td align="right">
																<?php echo number_format($rst_p->drr_procentaje_retencion, $dec)?> 
															</td>
															<td align="right">
																<?php echo number_format($rst_p->drr_valor, $dec)?> 
															</td>
														</tr>
														<?php    
													}
													?>
												</tbody>	
											</table>
										</div>	
									</td>	
									<td>
										<div class="col-md-3">
											<table class="table tabla table-bordered table-list table-hover" id="tabla9">
												<thead id="thead9">
													<tr>
														<th colspan="2">Total Compras</th>
													</tr>
													<tr>
														<th colspan="2">Facturas</th>
													</tr>
												</thead>
												<tr>
													<td>Subtotal</td>
													<td align='right' style='font-size:14px;'> <?php echo number_format($c_fsubt, $dec) ?> </td>

												</tr>   
												<tr>    
													<td>Descuento</td>
													<td align='right' style='font-size:14px;'> <?php echo number_format($c_fdesc, $dec) ?> </td>    
												</tr>   
												<tr>    
													<td>Subtotal 0%</td>
													<td align='right' style='font-size:14px;'> <?php echo number_format($c_fiva0, $dec) ?> </td>
												</tr>   
												<tr>    
													<td>Subtotal 12%</td>
													<td align='right' style='font-size:14px;'> <?php echo number_format($c_fiva12, $dec) ?> </td>    
												</tr>   
												<tr>    
													<td>IVA</td>
													<td align='right' style='font-size:14px;'> <?php echo number_format($c_tiva, $dec) ?> </td>                        
												</tr>   
												<tr>
													<td>Total $</td>
													<td align='right' style='font-size:14px;'> <?php echo number_format($c_ftotal, $dec) ?> </td>
												</tr>
												<thead>
													<tr>
														<th colspan="2">Notas de Credito</td>
														</tr>
													</thead>    
													<tr>
														<td>Subtotal</td>
														<td align='right' style='font-size:14px;'> <?php echo number_format($c_ncsubt+$nc_ncsubt, $dec) ?> </td>

													</tr>
													<tr>    
														<td>Descuento</td>
														<td align='right' style='font-size:14px;'> <?php echo number_format($c_ncdesc+$nc_ncdesc, $dec) ?> </td>    
													</tr>
													<tr>    
														<td>Subtotal 0%</td>
														<td align='right' style='font-size:14px;'> <?php echo number_format($c_nciva0+$nc_nciva0, $dec) ?> </td>
													</tr>
													<tr>    
														<td>Subtotal 12%</td>
														<td align='right' style='font-size:14px;'> <?php echo number_format($c_nciva12+$nc_nciva12, $dec) ?> </td>
													</tr>
													<tr>    
														<td>IVA</td>
														<td align='right' style='font-size:14px;'> <?php echo number_format($c_nciva+$nc_nciva, $dec) ?> </td>    
													</tr>
													<tr>    
														<td>Total $</td>
														<td align='right' style='font-size:14px;'> <?php echo number_format($c_nctotal+$nc_nctotal, $dec) ?> </td>
													</tr>
													<thead>   
														<tr>
															<th colspan="2">Retencion</td>
															</tr>
														</thead> 
														<?php
														$t_retivac=round($c_retiva, $dec)+round($rc_retiva, $dec);
														$t_retrentc=round($c_retrent, $dec)+round($rc_retrent, $dec);
														$t_retvalc=round($t_retivac+$t_retrentc, $dec);   
														?>
														<tr>  
															<td>IVA</td>
															<td align='right' style='font-size:14px;'> <?php echo number_format($t_retivac, $dec) ?> </td>    
														</tr>
														<tr>
															<td>Renta</td>
															<td align='right' style='font-size:14px;'> <?php echo number_format($t_retrentc, $dec) ?> </td>
														</tr>
														<tr>
															<td>Valor</td> 
															<td align='right' style='font-size:14px;'> <?php echo number_format($t_retvalc, $dec) ?></td>    
														</tr>
													</table>
												</div>
											</td>
											<td valign="top">
												<div class="col-md-3">
													<table class="table tabla table-bordered table-list table-hover" id="tabla10">
														<thead id="thead10">
															<tr>
																<th colspan="3">Retenciones Compras</th>
															</tr>
															<tr>
																<th>Impuesto</th>
																<th>Porcentaje</th>
																<th>Valor</th>
															</tr>
														</thead>
														<tbody>
															<?php
															foreach ($cns_p2 as $rst_p2) {
																if($rst_p2->dtr_tipo_impuesto=='IV'){
																	$imp2='IVA';
																}else if($rst_p2->dtr_tipo_impuesto=='IR'){
																	$imp2='RENTA';
																}
																?>
																<tr>
																	<td>
																		<?php echo $imp2 ?> 
																	</td>
																	<td align="right">
																		<?php echo number_format($rst_p2->dtr_procentaje_retencion, $dec)?> 
																	</td>
																	<td align="right">
																		<?php echo number_format($rst_p2->dtr_valor, $dec)?> 
																	</td>
																</tr>
																<?php    
															}
															?>
														</tbody>
													</table>
												</div>		

											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<style type="text/css">
							thead{
								background: #3c8dbc !important;
								color: #fff !important;
							}
						</style>

					</section>

