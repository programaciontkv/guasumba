
<section class="content-header">
	  	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>cierre_caja/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
       	<h1>
        Cierres de Caja <?php echo $titulo?>
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			 <?php 
	          if($this->session->flashdata('error')){
	            ?>
	            <div class="alert alert-danger alert-dismissible">
	              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	              <p><i class="icon fa fa-ban"></i> <?php echo $this->session->flashdata('error')?></p>
	            </div>
	            <?php
	          }
	          ?>
			<div class="row">
				
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
				<div class="col-md-1">
					<?php 
					if($permisos->rop_insertar){
					?>	
					<form action="<?php echo base_url();?>cierre_caja/guardar/<?php echo $permisos->opc_id?>" method="post">
					<table>
						<tr>
							<td><label>Fecha:</label></td>
							<td><input type="date" id='fecha' name='fecha' class="form-control" style="width: 150px" value='<?php echo $fecha?>' />
							</td>
							<td><button type="submit" class="btn btn-primary btn-flat"><span class="fa fa-plus"></span> Cerrar Caja</button></td>
						</tr>
					</table>
					</form>	
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
							<th>No.Documento</th>
							<th>Fecha</th>
							<th>Hora</th>
							<th>Vendedor</th>
							<th>Total Facturas</th>
							<th>Total Notas Credito</th>
							<th>Estado</th>
							<th>Acciones</th>
						</thead>
						<tbody>
						<?php 
				        $dec=$dec->con_valor;
						$n=0;
						if(!empty($cierres)){
							foreach ($cierres as $cierre) {
								$n++;
						?>
							<tr>
								<td><?php echo $n?></td>
								<td style="mso-number-format:'@'"><?php echo $cierre->cie_secuencial?></td>
								<td><?php echo $cierre->cie_fecha?></td>
								<td style="mso-number-format:'@'"><?php echo $cierre->cie_hora?></td>
								<td><?php echo $cierre->vnd_nombre?></td>
								<td style="text-align: right;"><?php echo number_format($cierre->cie_total_facturas,$dec)?></td>
								<td style="text-align: right;"><?php echo number_format($cierre->cie_total_notas_credito,$dec)?></td>
								<td><?php echo $cierre->est_descripcion?></td>
								<td align="center">
									<div class="btn-group">
										
										<?php 
							        	if($permisos->rop_reporte){
										?>
											<a href="<?php echo base_url();?>cierre_caja/show_frame/<?php echo $cierre->cie_secuencial?>/<?php echo $permisos->opc_id?>" class="btn btn-warning" title="Reporte"> <span class="fa fa-file-pdf-o" ></span></a>
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
                <h4 class="modal-title">Cierre</h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>
