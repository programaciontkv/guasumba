<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>auditoria/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Auditoria
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-12">
					<form action="<?php echo $buscar;?>" method="post">
						
					<table width="100%">
						<tr>
							<td><label>Buscar:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>' placeholder='Documento'/></td>
							<td><label>Usuario:</label></td>
							<td>
								<select name="usuario" id="usuario" class="form-control">
									<option value="">SELECCIONE</option>
									<?php
									foreach($usuarios as $usu){
									?>
									<option value="<?php echo $usu->usu_login?>"><?php echo $usu->usu_login?></option>
									<?php	
									}
									?>
								</select>
							</td>
							<td><label>Accion:</label></td>
							<td>
								<select name="accion" id="accion" class="form-control">
									<option value="">SELECCIONE</option>
									<?php
									foreach($acciones as $acc){
									?>
									<option value="<?php echo $acc->adt_accion?>"><?php echo $acc->adt_accion?></option>
									<?php	
									}
									?>
								</select>
								<script type="text/javascript">
									var usu='<?php echo $usuario?>';
									usuario.value=usu;
									var accio='<?php echo $accion?>';
									accion.value=accio;
								</script>
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
							<th>Usuario</th>
							<th>Fecha</th>
							<th>Hora</th>
							<th>Modulo</th>
							<th>Accion</th>
							<th>Documento</th>
							<th>Ip</th>
							<th>Acciones</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($auditorias)){
							foreach ($auditorias as $auditoria) {
								$n++;
						?>
							<tr>
								<td><?php echo $n?></td>
								<td style="mso-number-format:'@'"><?php echo $auditoria->usu_login?></td>
								<td><?php echo $auditoria->adt_date?></td>
								<td style="mso-number-format:'@'"><?php echo $auditoria->adt_hour?></td>
								<td><?php echo $auditoria->adt_modulo?></td>
								<td><?php echo $auditoria->adt_accion?></td>
								<td><?php echo $auditoria->adt_documento?></td>
								<td><?php echo $auditoria->adt_ip?></td>
								<td align="center">
									<div class="btn-group">
										<?php
										if($auditoria->adt_campo!=''){
										?>
											<button type="button" class="btn btn-info btn-view" data-toggle="modal" data-target="#modal-default" value="<?php echo base_url();?>auditoria/visualizar/<?php echo $auditoria->adt_id?>"><span class="fa fa-eye"></span>
								            </button>
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
                <h4 class="modal-title">Auditoria</h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>