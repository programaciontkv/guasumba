<section class="content-header">
      <h1>
        Productos
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
						<a href="<?php echo base_url();?>producto/nuevo" class="btn btn-primary btn-flat"><span class="fa fa-plus"></span> Nuevo</a>
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
							<th>Ancho</th>
							<th>Largo</th>
							<th>Densidad</th>
							<th>Espesor</th>
							<th>Peso</th>
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
								<td><?php echo $producto->pro_codigo?></td>
								<td><?php echo $producto->pro_descripcion?></td>
								<td><?php echo $producto->pro_uni?></td>
								<td><?php echo $producto->pro_ancho?></td>
								<td><?php echo $producto->pro_largo?></td>
								<td><?php echo $producto->pro_por_tornillo3?></td>
								<td><?php echo $producto->pro_espesor?></td>
								<td><?php echo $producto->pro_peso?></td>
								<td><?php echo $producto->est_descripcion?></td>
								<td align="center">
									<div class="btn-group">
										<?php 
							        	if($permisos->rop_reporte){
										?>
											<a href="<?php echo base_url();?>producto/show_frame/<?php echo $producto->pro_id?>" class="btn btn-success"> <span class="fa fa-file-pdf-o"></span></a>
										<?php 
										}
										if($permisos->rop_actualizar){
										?>
											<a href="<?php echo base_url();?>producto/editar/<?php echo $producto->pro_id?>" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
										<?php 
										}
										if($permisos->rop_eliminar){
										?>
										<a href="<?php echo base_url();?>producto/eliminar/<?php echo $producto->pro_id?>/<?php echo $producto->pro_codigo?>" class="btn btn-danger btn-remove"><span class="fa fa-trash"></span></a>
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
                <h4 class="modal-title">Producto</h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>