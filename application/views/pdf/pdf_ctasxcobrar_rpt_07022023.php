<section class="content-header">
	  	
       	<h1>
        Central de Cobranzas <?php echo $titulo?>
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-12">
					
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
							<th>Total</th>
							<th>Pagado</th>
							<th>Saldo</th>
							<th>Estado</th>
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
									<a href="<?php echo base_url();?>ctasxcobrar/buscar_reporte/<?php echo $factura->fac_identificacion?>/<?php echo $permisos->opc_id?>/0" class="btn btn-danger" title="Reporte Mora"> Mora</a>
								</td>
								<td><a href="<?php echo base_url();?>ctasxcobrar/buscar_reporte/<?php echo $factura->fac_identificacion?>/<?php echo $permisos->opc_id?>/6" class="btn btn-danger" title="Reporte Estado de Cobros">Estado Cobros</a></td>
							</tr>
						<?php		
							}
						?>
						
							<tr <?php echo $estado?>>
								<td><?php echo $n?></td>				
								<td><a href="<?php echo base_url();?>factura/show_frame/<?php echo $factura->fac_id?>/<?php echo $permisos->opc_id?>" > <?php echo $factura->fac_numero?></a></td>
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
											<a href="<?php echo base_url();?>ctasxcobrar/show_frame/<?php echo $factura->fac_id?>/<?php echo $permisos->opc_id?>" class="btn btn-danger" title="Detalle"> <span class="fa fa-file-pdf-o" ></span></a>
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


<script type="text/javascript">

    
    window.onload = function () {
      window.print();
    }
    
</script>
