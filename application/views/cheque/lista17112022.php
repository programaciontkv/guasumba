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
					<form action="<?php echo $buscar;?>" method="post">
						
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
							<th>Fecha Recepcion</th>
							<th>Tipo Documento</th>
							<th>Cliente</th>
							<th>Nombre del Cheque</th>
							<th>Banco</th>
							<th>No Cheque</th>
							<th>Valor</th>
							<th>Saldo</th>
							<th>Estado Cobro</th>
							<th>Estado Cheque</th>
							<th>Ajuste</th>
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
								<td><?php echo $cheque->chq_recepcion?></td>
								<td><?php echo $tp?></td>
								<td style="mso-number-format:'@'"><?php echo $cheque->cli_raz_social?></td>
								<td style="mso-number-format:'@'"><?php echo $cheque->chq_nombre?></td>
								<td style="mso-number-format:'@'"><?php echo $cheque->btr_descripcion?></td>
								<td style="mso-number-format:'@'"><?php echo $cheque->chq_numero?></td>
								<td class="number"><?php echo str_replace(',', '', number_format($cheque->chq_monto,$dec))?></td>
								<td class="number"><?php echo str_replace(',', '', number_format($saldo,$dec))?></td>
								<td><?php echo $cheque->est_descripcion?></td>
								<td>
									<?php 
									if($cheque->chq_estado_cheque!=12 && $cheque->chq_estado_cheque!=3){
									?>
									<a href="<?php echo base_url();?>cheque/cambiar_estado/<?php echo $cheque->chq_id?>/<?php echo $opc_id?>" class="btn btn-default" title="Cambiar Estado"><?php echo $cheque->est_cheque?></a>
									<?php
									}else{
									?>
									<?php echo $cheque->est_cheque?>
									<?php
									}
									?>
								</td>
								<td align="center">
									<div class="btn-group">
										
										<?php 
							        	if($permisos->rop_reporte){
										?>
											<a href="<?php echo base_url();?>cheque/show_frame/<?php echo $cheque->chq_id?>/<?php echo $permisos->opc_id?>" class="btn btn-warning" title="RIDE"> <span class="fa fa-file-pdf-o" ></span></a>
										<?php 
										}
										if($cheque->chq_estado_cheque==11 && $cheque->chq_estado!=9){
										?>	
										<a href="<?php echo base_url();?>cheque/cobrar/<?php echo $cheque->chq_id?>/<?php echo $opc_id?>/<?php echo$cheque->cli_id?>" class="btn btn-info" title="Cobrar"> <span class="fa fa-table" ></span></a>
										<?php
										}	
										if($permisos->rop_actualizar){
											if($cheque->chq_estado==7 && $cheque->chq_estado_cheque==10){
										?>
												<a href="<?php echo base_url();?>cheque/editar/<?php echo $cheque->chq_id?>/<?php echo $opc_id?>" class="btn btn-primary" title="Editar"> <span class="fa fa-edit" ></span></a>
										<?php 
											}
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

