<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>reg_nota_credito/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Registro Nota de Credito <?php echo $titulo?>
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
						<a href="<?php echo base_url();?>reg_nota_credito/nuevo/<?php echo $permisos->opc_id?>" class="btn btn-primary btn-flat"><span class="fa fa-plus"></span> Nuevo</a>
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
					<table id="tbl_list" class="table table-bordered table-list table-hover">
						<thead>
							<th>No</th>
							<th>Fecha</th>
							<th>Nota Credito No</th>
							<th>Factura</th>
							<th>Ruc</th>
							<th>Cliente</th>
							<th>Total Nota Cred.$</th>
							<th>Total Factura $</th>
							<th>Estado</th>
							<th>Acciones</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($notas)){
							foreach ($notas as $nota) {
								$n++;
						?>
							<tr>
								<td><?php echo $n?></td>
								<td><?php echo $nota->rnc_fecha_emision?></td>
								<td><?php echo $nota->rnc_numero?></td>
								<td><?php echo $nota->rnc_num_comp_modifica?></td>
								<td style="mso-number-format:'@'"><?php echo $nota->rnc_identificacion?></td>
								<td><?php echo $nota->rnc_nombre?></td>
								<td><?php echo $nota->rnc_total_valor?></td>
								<td><?php echo $nota->reg_total?></td>
								<td><?php echo $nota->est_descripcion?></td>
								<td align="center">
									<div class="btn-group">
										<?php 
							        	if($permisos->rop_reporte){
										?>
											<a href="<?php echo base_url();?>reg_nota_credito/show_frame/<?php echo $nota->rnc_id?>/<?php echo $permisos->opc_id?>" class="btn btn-warning"> <span class="fa fa-file-pdf-o"></span></a>
										<?php 
										}
										if($permisos->rop_actualizar){
											if($nota->rnc_estado!=3){
										?>
												<a href="<?php echo base_url();?>reg_nota_credito/editar/<?php echo $nota->rnc_id?>/<?php echo $opc_id?>" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
										<?php 
											}
										}
										if($permisos->rop_eliminar){
											if($nota->rnc_estado!=3){
										?>
												<a href="<?php echo base_url();?>reg_nota_credito/anular/<?php echo $nota->rnc_id?>/<?php echo $nota->rnc_numero?>/<?php echo $permisos->opc_id?>" class="btn btn-danger btn-anular-comp"><span class="fa fa-trash"></span></a>
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

<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">nota</h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>