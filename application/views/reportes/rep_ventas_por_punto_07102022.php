<section class="content-header">
	  	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>rep_ventas_por_punto/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
       	<h1>
        Ventas por Punto de Facturacion
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				
				<div class="col-md-10">
					<form id='frm_buscar' action="<?php echo $buscar;?>" method="post">
						
					<table width="100%">
						<tr>
							<td class="hidden-mobile" ><label>Empresa:</label></td>
							<td class="hidden-mobile">
								<select id="empresa" name="empresa" class="form-control">
									<?php
									if(!empty($empresas)){
										foreach ($empresas as $emp) {
									?>
									<option value="<?php echo $emp->emp_id?>"><?php echo $emp->emp_nombre?></option>
									<?php		
										}
									}
									?>
									
								</select>
								<script>
									var emp='<?php echo $empresa?>';
									empresa.value=emp;
								</script>
							</td>
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control"  value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control" value='<?php echo $fec2?>' /></td>
							
							<td class="hidden-mobile" ><label>Vendedor:</label><input type="checkbox" id='vendedor' name='vendedor' onclick="enviar()" <?php echo $vendedor?>/></td>
							<td class="hidden-mobile"><label>Ventas:</label><input type="checkbox" id='ventas' name='ventas' onclick="enviar()" <?php echo $ventas?>/></td>
							<td class="hidden-mobile"><label>Devoluciones:</label><input type="checkbox" id='devoluciones' name='devoluciones' onclick="enviar()" <?php echo $devoluciones?>/></td>
							<td class="hidden-mobile"><label>Caja:</label><input type="checkbox" id='cajab' name='cajab' onclick="enviar()" <?php echo $cajab?>/></td>
							<td>
							</td>
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
					<table id="tbl_list" class="table table-bordered">
						<thead>
							<tr>
								<th></th>
								<?php 
									if($ventas=='checked'){
								?>	
									<th colspan="7">Ventas</th>
								<?php 
									}
								?>
								<?php 
									if($devoluciones=='checked'){
								?>
								<th class="hidden-mobile" colspan="6">Devoluciones</th>
								<th class="hidden-mobile">Total</th>
								<?php 
									}
								?>
								<?php 
									if($cajab=='checked'){
								?>
								<th class="hidden-mobile" colspan="10">Cierre Caja</th>
								<?php 
									}
								?>
							</tr>	
							<tr>	
								<th>Local/Vendedor</th>
								<?php 
									if($ventas=='checked'){
								?>
								<th class='hidden-mobile'>#Factura</th>
								<th>Desc.</th>
								<th>Sbt.IVA</th>
								<th>Sbt.sin IVA</th>
								<th>Sbt Neto</th>
								<th class="hidden-mobile">ICE</th>
								<th>IVA</th>
								<th>Tot.Vnt</th>
								<?php 
									}
								?>
								<?php 
									if($devoluciones=='checked'){
								?>
								<th class="hidden-mobile">#NC</th>
								<th class="hidden-mobile" >Sbt.IVA</th>
								<th class="hidden-mobile" >Sbt.sin IVA</th>
								<th class="hidden-mobile" >Sbt Neto</th>
								<th class="hidden-mobile" >IVA</th>
								<th class="hidden-mobile" >Tot.Dev</th>
								<th class="hidden-mobile">Vnt-Dev</th>
								<?php 
									}
								?>
								<?php 
									if($cajab=='checked'){
								?>
								<th class="hidden-mobile" >TC</th>
								<th class="hidden-mobile"class="hidden-mobile">TD</th>
								<th class="hidden-mobile">Cheque</th>
								<th class="hidden-mobile">Efectivo</th>
								<th class="hidden-mobile">Certificados</th>
								<th class="hidden-mobile">Transferencia</th>
								<th class="hidden-mobile">Retencion</th>
								<th class="hidden-mobile">NC</th>
								<th class="hidden-mobile">Credito</th>
								<th class="hidden-mobile">Tot.Caja</th>
								<?php 
									}
								?>
							</tr>	
						</thead>
						<tbody>
						<?php 
							echo $detalle;
						?>
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>


</section>

<style>
.subtotal{
	/*background: #A2CADF;*/
	background: #4682b4;
	color: #FFFFFF;
	font-weight: bolder;
	font-size: 14px;
}
.total{
	background: #3e5f8a;
	color: #FFFFFF;
	font-weight: bolder;
	font-size: 14px;
}
.local{
	font-weight: bolder;
}
	
</style>
<script>
	function enviar(){
		$('#frm_buscar').submit();
	}
</script>