<section class="content-header">
      <form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>pedido_bodega/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Pedidos de Bodegas <?php echo $titulo?>
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
				<div class="col-md-1">
					<?php 
					$dec=$dec->con_valor;
					if($permisos->rop_insertar){
					?>
					<form id="frm_transferir" action="<?php echo base_url();?>pedido_bodega/nuevo/<?php echo $permisos->opc_id?>" method="post">
						<input type="hidden" name="txt_data" id="txt_data">
						<button  type="button" class="btn btn-primary btn-flat" onclick="transferencia()"> Transferir</button>
					<?php 
					}
					?>
					</form>
				</div>
			
				<div class="col-md-7">
					<form action="<?php echo $buscar;?>" method="post">
						
					<table width="100%">
						<tr>
							<td><label>Buscar:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td><label>Estado:</label></td>
							<td></td>
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
							<th>Orden de venta</th>
							<th>Ruc/Cedula</th>
							<th>Cliente</th>
							<th>Local</th>
							<th>Vendedor</th>
							<th>Total Valor</th>
							<th>Estado</th>
							<th>Acciones</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($pedidos)){
							foreach ($pedidos as $pedido) {
								$n++;
						?>
							<tr>
								<td><?php echo $n?></td>
								<td><?php echo $pedido->ped_femision?></td>
								<td style='mso-number-format:"@"'><?php echo $pedido->ped_num_registro?></td>
								<td style='mso-number-format:"@"'><?php echo $pedido->ped_ruc_cc_cliente?></td>
								<td id="cliente<?php echo $pedido->ped_id?>"><?php echo $pedido->ped_nom_cliente?></td>
								<td ><?php echo $pedido->emi_nombre?></td>
								<td><?php echo $pedido->vnd_nombre?></td>
								<td style="text-align: right;"><?php echo number_format($pedido->ped_total,2)?></td>
								<td><?php echo $pedido->est_descripcion?></td>
								<td align="center">
									<div class="btn-group">
									<?php 
							        if($pedido->ped_estado==7 || $pedido->ped_estado==19){
									?>
										<input type="checkbox" class="check" name="chk<?php echo $pedido->ped_id?>" id="chk<?php echo $pedido->ped_id?>" lang="<?php echo $pedido->ped_id?>" onclick="seleccion()">
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
                <h4 class="modal-title">Pedido</h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>
<script type="text/javascript">
	function seleccion(){
		var cliente="";
		$('.check').each(function () {
	        var i = this.lang;
	        if($('#chk'+i).prop('checked')==true || $('#chk'+i).prop('checked')=='checked'){
	        	if(cliente==""){
	        		cliente=$('#cliente'+i).html();
	        	}else if(cliente!=$('#cliente'+i).html()){
	        		alert("Debe seleccionar pedidos del mismo cliente");
	        		$('#chk'+i).prop('checked','')
	        	}
	        }
        })
    } 
        
	function transferencia(){
		var data=Array();
		var local="";
		$('.check').each(function () {
	        var i = this.lang;
	        if($('#chk'+i).prop('checked')==true || $('#chk'+i).prop('checked')=='checked'){
	        	data.push(i);
	        }
        })
        if(data.length==0){
        	alert("Seleccione al menos un pedido");
        }else{
        	$('#txt_data').val(data);
        	$('#frm_transferir').submit();
        }
	}
</script>