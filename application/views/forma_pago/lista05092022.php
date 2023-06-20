<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>forma_pago/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Formas de Pago
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-12">
					<?php 
					if($permisos->rop_insertar){
					?>
						<a href="<?php echo base_url();?>forma_pago/nuevo" class="btn btn-primary btn-flat"><span class="fa fa-plus"></span> Nuevo</a>
					<?php 
					}
					?>
				</div>	
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-list table-hover">
						<thead>
							<th>No</th>
							<th>Codigo SRI</th>
							<th>Descripcion</th>
							<th>Tipo</th>
							<th>Banco</th>
							<th>Tajeta</th>
							<!-- <th>Codigo Cuenta</th>
							<th>Descripcion Cuenta</th> -->
							<th>Precio</th>
							<th>Estado</th>
							<th>Acciones</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($formas_pago)){
							foreach ($formas_pago as $forma_pago) {
								$n++;
								switch ($forma_pago->fpg_tipo) {
									case "0": $tipo=""; break;
									case "1": $tipo="TARJETA DE CREDITO"; break;
				                    case "2": $tipo="TARJETA DE DEBITO"; break;
				                    case "3": $tipo="CHEQUE"; break;
				                    case "4": $tipo="EFECTIVO"; break;
				                    case "5": $tipo="CERTIFICADOS"; break;
				                    case "6": $tipo="TRANSFERENCIA"; break;
				                    case "7": $tipo="RETENCION"; break;
				                    case "8": $tipo="NOTA CREDITO"; break;
				                    case "9": $tipo="CREDITO"; break;
				                }
								switch ($forma_pago->fpg_banco) {
									case "0": $banco=""; break;
									case "1": $banco="Banco Pichincha"; break;
				                    case "2": $banco="Banco del Pacífico"; break;
				                    case "3": $banco="Banco de Guayaquil"; break;
				                    case "4": $banco="Produbanco"; break;
				                    case "5": $banco="Banco Bolivariano"; break;
				                    case "6": $banco="Banco Internacional"; break;
				                    case "7": $banco="Banco del Austro"; break;
				                    case "8": $banco="Banco Promerica (Ecuador)"; break;
				                    case "9": $banco="Banco de Machala"; break;
				                    case "10": $banco="BGR"; break;
				                    case "11": $banco="Citibank (Ecuador)"; break;
				                    case "12": $banco="Banco ProCredit (Ecuador)"; break;
				                    case "13": $banco="UniBanco"; break;
				                    case "14": $banco="Banco Solidario"; break;
				                    case "15": $banco="Banco de Loja"; break;
				                    case "16": $banco="Banco Territorial"; break;
				                    case "17": $banco="Banco Coopnacional"; break;
				                    case "18": $banco="Banco Amazonas"; break;
				                    case "19": $banco="Banco Capital"; break;
				                    case "20": $banco="Banco D-MIRO"; break;
				                    case "21": $banco="Banco Finca"; break;
				                    case "22": $banco="Banco Comercial de Manabí"; break;
				                    case "23": $banco="Banco COFIEC"; break;
				                    case "24": $banco="Banco del Litoral"; break;
				                    case "25": $banco="Banco Delbank"; break;
				                    case "26": $banco="Banco Sudamericano"; break;
								}

								switch ($forma_pago->fpg_tarjeta) {
									case "0": $tarjeta=""; break;
									case "1": $tarjeta="VISA"; break;
				                    case "2": $tarjeta="MASTER CARD"; break;
				                    case "3": $tarjeta="AMERICAN EXPRESS"; break;
				                    case "4": $tarjeta="DINNERS"; break;
				                    case "5": $tarjeta="DISCOVER"; break;
				                }    
						?>
							<tr>
								<td><?php echo $n?></td>
								<td style="mso-number-format:'@'"><?php echo $forma_pago->fpg_codigo?></td>
								<td><?php echo $forma_pago->fpg_descripcion?></td>
								<td><?php echo $tipo?></td>
								<td><?php echo $forma_pago->banco?></td>
								<td><?php echo $forma_pago->tarjeta?></td>
								<!-- <td style="mso-number-format:'@'"><?php echo $forma_pago->pln_codigo?></td>
								<td><?php echo $forma_pago->pln_descripcion?></td> -->
								<td><?php echo $forma_pago->fpg_precio?></td>
								<td><?php echo $forma_pago->est_descripcion?></td>
								<td align="center">
									<div class="btn-group">
										<?php 
										if($permisos->rop_reporte){
										?>
											<button type="button" class="btn btn-info btn-view" data-toggle="modal" data-target="#modal-default" value="<?php echo base_url();?>forma_pago/visualizar/<?php echo $forma_pago->fpg_id?>"><span class="fa fa-eye"></span>
								            </button>
							            <?php
							        	}
										if($permisos->rop_actualizar){
										?>
											<a href="<?php echo base_url();?>forma_pago/editar/<?php echo $forma_pago->fpg_id?>" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
										<?php 
										}
										if($permisos->rop_eliminar){
										?>
										<a href="<?php echo base_url();?>forma_pago/eliminar/<?php echo $forma_pago->fpg_id?>/<?php echo $forma_pago->fpg_descripcion?>" class="btn btn-danger btn-remove"><span class="fa fa-trash"></span></a>
										<?php 
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

<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">forma_pago</h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>