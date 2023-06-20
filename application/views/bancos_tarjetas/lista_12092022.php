<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>bancos_tarjetas/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Bancos, Tarjetas y Plazos
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
						<a href="<?php echo base_url();?>bancos_tarjetas/nuevo" class="btn btn-primary btn-flat"><span class="fa fa-plus"></span> Nuevo</a>
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
							<th>Tipo</th>
							<th>Forma de Pago</th>
							<th>Descripcion</th>
							<th>Estado</th>
							<th>Acciones</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($bancos_tarjetas)){
							foreach ($bancos_tarjetas as $banco_tarjeta) {
								$n++;
								switch ($banco_tarjeta->btr_forma) {
									case "0": $forma=""; break;
									case "1": $forma="TARJETA DE CREDITO"; break;
				                    case "2": $forma="TARJETA DE DEBITO"; break;
				                    case "3": $forma="CHEQUE"; break;
				                    case "4": $forma="EFECTIVO"; break;
				                    case "5": $forma="CERTIFICADOS"; break;
				                    case "6": $forma="TRANSFERENCIA"; break;
				                    case "7": $forma="RETENCION"; break;
				                    case "8": $forma="NOTA CREDITO"; break;
				                    case "9": $forma="CREDITO"; break;
				                }

				                switch ($banco_tarjeta->btr_tipo) {
									case "0": $tipo="BANCO"; break;
									case "1": $tipo="TARJETA"; break;
				                    case "2": $tipo="PLAZO"; break;
				                }
								
						?>
							<tr>
								<td><?php echo $n?></td>
								<td><?php echo $tipo?></td>
								<td><?php echo $forma?></td>
								<td><?php echo $banco_tarjeta->btr_descripcion?></td>
								<td><?php echo $banco_tarjeta->est_descripcion?></td>
								<td align="center">
									<div class="btn-group">
										<?php 
										if($permisos->rop_reporte){
										?>
											<button type="button" class="btn btn-info btn-view" data-toggle="modal" data-target="#modal-default" value="<?php echo base_url();?>bancos_tarjetas/visualizar/<?php echo $banco_tarjeta->btr_id?>"><span class="fa fa-eye"></span>
								            </button>
							            <?php
							        	}
										if($permisos->rop_actualizar){
										?>
											<a href="<?php echo base_url();?>bancos_tarjetas/editar/<?php echo $banco_tarjeta->btr_id?>" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
										<?php 
										}
										if($permisos->rop_eliminar){
										?>
										<a href="<?php echo base_url();?>bancos_tarjetas/eliminar/<?php echo $banco_tarjeta->btr_id?>/<?php echo $banco_tarjeta->btr_descripcion?>" class="btn btn-danger btn-remove"><span class="fa fa-trash"></span></a>
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
                <h4 class="modal-title">Bancos, Tarjetas y Plazos </h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>