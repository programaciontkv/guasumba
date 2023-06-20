<section class="content-header">
      <form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>pedido_factura/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Pedidos a Despachar
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			
				<div class="col-md-10">
					<form action="<?php echo $buscar;?>" method="post">
						
					<table style="margin-left:-20px">
						<tr>
							<td class="hidden-mobile"><label>Buscar:</label></td>
							<td class="hidden-mobile"><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td class="hidden-mobile"><label>Estado:</label></td>
							<td></td>
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control"  value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control"  value='<?php echo $fec2?>' /></td>
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
						<!-- 	<th>No</th> -->
							<th>Fecha</th>
							<th>Orden de venta</th>
							<th class="hidden-mobile">Ruc/Cedula</th>
							<th>Cliente</th>
							<th class="hidden-mobile">Local</th>
							<th class="hidden-mobile">Vendedor</th>
							<th class="hidden-mobile">Total Valor</th>
							<th>Estado</th>
							<th>Ajustes</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($pedidos)){
							foreach ($pedidos as $pedido) {
								$n++;
						?>
							<tr>
							<!-- 	<td><?php echo $n?></td> -->
								<td><?php echo $pedido->ped_femision?></td>
								<td style='mso-number-format:"@"'><?php echo $pedido->ped_num_registro?></td>
								<td class="hidden-mobile" style='mso-number-format:"@"'><?php echo $pedido->ped_ruc_cc_cliente?></td>
								<td><?php echo $pedido->ped_nom_cliente?></td>
								<td class="hidden-mobile"><?php echo $pedido->emi_nombre?></td>
								<td class="hidden-mobile"><?php echo $pedido->vnd_nombre?></td>
								<td class="hidden-mobile" style="text-align: right;"><?php echo number_format($pedido->ped_total,2)?></td>
								<td><?php echo $pedido->est_descripcion?></td>
								<td align="center">
									<div class="btn-group">
									<?php 
							        if($pedido->ped_estado==13 || $pedido->ped_estado==15){
									?>
										<a title="Facturar" href="<?php echo base_url();?>pedido_factura/nuevo/<?php echo $pedido->ped_id?>/<?php echo $opc_id?>" class="btn btn-primary"> <span class="fa fa-shopping-cart"></span></a>
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
                <h4 class="modal-title">pedido</h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>