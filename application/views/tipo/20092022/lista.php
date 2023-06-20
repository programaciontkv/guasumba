<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>tipo/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Categorias y Clasificaciones
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
						<a href="<?php echo base_url();?>tipo/nuevo" class="btn btn-primary btn-flat"><span class="fa fa-plus"></span> Nuevo</a>
					<?php 
					}
					?>
				</div>	
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-list table-hover table-responsive">
						<thead>
							<th>No</th>
							<th>Categoria</th>
							<th>Familia</th>
							<th>Nombre</th>
							<th>Relacion</th>
							<!-- <th>Desnidad</th> -->
							<th>Estado</th>
							<th>Acciones</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($tipos)){
							foreach ($tipos as $tipo) {
								$n++;
								if($tipo->tps_relacion=='1'){
									$relacion='FAMILIA';
								}else{
									$relacion='TIPO';
								}
						?>
							<tr>
								<td><?php echo $n?></td>
								<td><?php echo $tipo->cat_descripcion?></td>
								<td><?php echo $tipo->familia?></td>
								<td><?php echo $tipo->tps_nombre?></td>
								<td><?php echo $relacion?></td>
							<!-- 	<td><?php echo $tipo->tps_densidad?></td> -->
								<td><?php echo $tipo->est_descripcion ?></td>
								<td align="center">
									<div class="btn-group">
										<?php 
										if($permisos->rop_reporte){
										?>
											<button type="button" class="btn btn-info btn-view" data-toggle="modal" data-target="#modal-default" value="<?php echo base_url();?>tipo/visualizar/<?php echo $tipo->tps_id?>"><span class="fa fa-eye"></span>
								            </button>
							            <?php
							        	}
										if($permisos->rop_actualizar){
										?>
											<a href="<?php echo base_url();?>tipo/editar/<?php echo $tipo->tps_id?>" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
										<?php 
										}
										if($permisos->rop_eliminar){
										?>
										<a href="<?php echo base_url();?>tipo/eliminar/<?php echo $tipo->tps_id?>/<?php echo $tipo->tps_nombre?>" class="btn btn-danger btn-remove"><span class="fa fa-trash"></span></a>
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
                <h4 class="modal-title">Categoria y Clasificacion</h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>