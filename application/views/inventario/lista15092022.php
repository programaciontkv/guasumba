<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>inventario/excel/<?php echo $permisos->opc_id?>/<?php echo $fec2?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Inventarios <?php echo $titulo?>
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-8">
					<form action="<?php echo $buscar;?>" method="post">
						
					<table width="100%">
						<tr>
							<td><label>Buscar:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td>
								<select name="tipo" id="tipo" class="form-control" style="width: 200px">
									<option value="26">PRODUCTO TERMINADO</option>
								<!-- 	<option value="69">MATERIA PRIMA</option> -->
								</select>
								<script>
									var tp="<?php echo $ids?>";
									tipo.value=tp;
								</script>
							</td>
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
					<table id="tbl_list" class="table table-bordered table-list table-hover table-striped" width="100%">
						<thead>
							<tr>
								<th>No</th>
								<th>Codigo</th>
								<th>Descripcion</th>
								<th>Unidad</th>
								<th>Cantidad</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$dec=$dec->con_valor;
						$dcc=$dcc->con_valor;
						$n=0;
						$inv=0;
						$tot=0;
						if(!empty($inventarios)){
							foreach ($inventarios as $inventario) {
								$n++;
								$inv=round($inventario->ingresos,$dcc)-round($inventario->egresos,$dcc);
								$tot+=$inv;    
						?>
							<tr>
								<td><?php echo $n?></td>
								<td style="mso-number-format:'@'"><?php echo $inventario->mp_c?></td>
								<td><?php echo $inventario->mp_d?></td>
								<td><?php echo $inventario->mp_q?></td>
								<td class="number"><?php echo str_replace(',','', number_format($inv,$dcc))?></td>
							</tr>
						<?php
							}
						}
						?>
							<tr>
								<th colspan="4">Totales</th>	
								<th class="number"><?php echo str_replace(',','', number_format($tot,$dcc))?></th>
							</tr>
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>

<style>
td{
	font-size: 11px !important;
}
.number{
	text-align: right !important;
}	
</style>
</section>

