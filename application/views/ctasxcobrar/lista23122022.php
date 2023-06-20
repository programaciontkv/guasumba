<section class="content-header">
	  	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>ctasxcobrar/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
       	<h1>
        Central de Cobranzas <?php echo $titulo?>
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
							<th>No</th>
							<th>Ruc</th>
							<th>Cliente</th>
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
							if($grup!=$factura->fac_identificacion){
						?>		
								
								
								
								<td style="mso-number-format:'@'"><?php echo $factura->fac_identificacion?></td>
								<td><?php echo $factura->fac_nombre?></td>
						<?php		
							}else{
						?>
								<td></td>
								<td></td>
						<?php		
							}
						?>					
								<td><?php echo $factura->fac_numero?></td>
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
											<a href="<?php echo base_url();?>ctasxcobrar/show_frame/<?php echo $factura->fac_id?>/<?php echo $permisos->opc_id?>" class="btn btn-warning" title="Detalle"> <span class="fa fa-file-pdf-o" ></span></a>
										<?php 
										}
										if($permisos->rop_insertar){
										?>
												<a href="<?php echo base_url();?>ctasxcobrar/nuevo/<?php echo $opc_id?>/<?php echo $factura->fac_id?>" class="btn btn-primary" title="Pagos"> <span class="fa fa-edit" ></span></a>
										<?php 
											}
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

