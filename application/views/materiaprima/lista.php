<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>materiaprima/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Materia Prima
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
						<a href="<?php echo base_url();?>materiaprima/nuevo" class="btn btn-primary btn-flat"><span class="fa fa-plus"></span> Nuevo</a>
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
							<th>Familia</th>
							<th>Tipo</th>
							<th>Codigo</th>
							<th>Descripcion</th>
							<th>Unidad</th>
							<th>Precio1</th>
							<th>Codigo Aux</th>
							<th>Imagen</th>
							<th>Estado</th>
							<th>Acciones</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($productos)){
							foreach ($productos as $producto) {
								$n++;
								
						?>
							<tr>
								<td><?php echo $n?></td>
								<td><?php echo $producto->tps_nombre?></td>
								<td><?php echo $producto->tip_nombre?></td>
								<td style="mso-number-format:'@'"><?php echo $producto->mp_c?></td>
								<td><?php echo $producto->mp_d?></td>
								<td><?php echo $producto->mp_q?></td>
								<td><?php echo $producto->mp_e?></td>
								<td style="mso-number-format:'@'"><?php echo $producto->mp_n?></td>
								<td class="imagen"><img id="fotografia" class="fotografia" src="<?php echo base_url().'imagenes/'.$producto->mp_aa ?>" width="100px" height="100px" class="form-control"></td>
								<td><?php echo $producto->est_descripcion?></td>
								<td align="center">
									<div class="btn-group">
										<?php 
							        	if($permisos->rop_reporte){
										?>
											<button type="button" class="btn btn-info btn-view" data-toggle="modal" data-target="#modal-default" value="<?php echo base_url();?>materiaprima/visualizar/<?php echo $producto->id?>"><span class="fa fa-eye"></span>
								            </button>
										<?php 
										}
										if($permisos->rop_actualizar){
										?>
											<a href="<?php echo base_url();?>materiaprima/editar/<?php echo $producto->id?>" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
										<?php 
										}
										if($permisos->rop_eliminar){
										?>
										<a href="<?php echo base_url();?>materiaprima/eliminar/<?php echo $producto->id?>/<?php echo $producto->mp_c?>" class="btn btn-danger btn-remove"><span class="fa fa-trash"></span></a>
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
                <h4 class="modal-title">Materia Prima</h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>