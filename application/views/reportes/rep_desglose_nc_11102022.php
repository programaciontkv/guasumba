<section class="content-header">
	  	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>rep_desglose_nc/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel2()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
       	<h1>
        Desglose de Notas de Credito
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				
				<div class="col-md-10">
					<form id='frm_buscar' action="<?php echo $buscar;?>" method="post" >
						
					<table width="100%">
						<tr>
							<td><label>Buscar Por:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 150px" value='<?php echo $txt?>' placeholder="NC/Factura/Cliente"/></td>
							<td><label>Local:</label></td>
							<td>
								<select id="emisor" name="emisor" class="form-control">
									<option value="0">SELECCIONE</option>
									<?php
									if(!empty($emisores)){
										foreach ($emisores as $emi) {
									?>
									<option value="<?php echo $emi->emi_id?>"><?php echo $emi->emi_nombre?></option>
									<?php		
										}
									}
									?>
									
								</select>
								<script>
									var emi='<?php echo $emisor?>';
									emisor.value=emi;
								</script>
							</td>
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control" style="width: 150px" value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control" style="width: 150px" value='<?php echo $fec2?>' /></td>
							<td><button type="button" class="btn btn-info" onclick="enviar()"><span class="fa fa-search" ></span> Buscar</button>
								</td>
						</tr>
					</table>
					</form>
				</div>			
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-striped table-list table-hover" width="100%">
						<thead id="tbl_thead">
								
							<tr>	
								<th>No</th>
								<th>Fecha</th>
								<th>Nota Credito No.</th>
								<th>Cliente</th>
								<th>Valor</th>
								<th>Fecha</th>
								<th>Factura No.</th>
								<th>Valor</th>
							</tr>	
						</thead>
						<tbody>
						<?php
							$dec=$dec->con_valor;
							$dcc=$dcc->con_valor;
							$t_nota=0;
							$t_factura=0;
							if(!empty($notas)){
								$n=0;

								$grup='';
								foreach ($notas as $nota) {
									$n++;
									if($nota->fac_fecha_emision=='1990-01-01'){
										$nota->fac_fecha_emision='';
									}
						?>
								<tr>
									<td><?php echo $n?></td>
									<td><?php echo $nota->ncr_fecha_emision?></td>
									<td><?php echo $nota->ncr_numero?></td>
									<td><?php echo $nota->ncr_nombre?></td>
									<td class="number"><?php echo number_format($nota->nrc_total_valor,$dec)?></td>
									<td><?php echo $nota->fac_fecha_emision?></td>
									<td><?php echo $nota->fac_numero?></td>
									<td class="number"><?php echo number_format($nota->fac_total_valor,$dec)?></td>
								</tr>

						<?php	
								$t_nota+=round($nota->nrc_total_valor,$dec);
								$t_factura+=round($nota->fac_total_valor,$dec);
								
								}
							}
						?>
						<tr class="total">
								<th>Total</th>
								<th></th>
								<th></th>
								<th></th>
								<th class="number"><?php echo number_format($t_nota,$dec)?></th>
								<th></th>
								<th></th>
								<th class="number"><?php echo number_format($t_factura,$dec)?></th>
								
							</tr>
						</tbody>
					
					</table>
				</div>	
			</div>
		</div>
	</div>


</section>


<script>
	function enviar(){
		if($('#emisor').val()=='0'){
			alert('Seleccione Local');
		}else{
			$('#frm_buscar').submit();
		}
	}
</script>