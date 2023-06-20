<style>
table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;

}

th, td {
  text-align: left;
  padding: 1px;
}

tr:nth-child(even){background-color: #f2f2f2}
</style>
<section class="content-header">
	  	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>rep_desglose_nc/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel2()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
       	<h1>
        Desglose de Guias de Remision
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
							<td class="hidden-mobile"><label>Buscar Por:</label></td>
							<td class="hidden-mobile"><input type="text" id='txt' name='txt' class="form-control" style="width: 150px" value='<?php echo $txt?>' placeholder="Guia/Factura/Cliente/Producto"/></td>
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
					<table id="tbl_list" class="" width="100%">
						<thead id="tbl_thead">
								
							<tr>	
								<th class="hidden-mobile">No</th>
								<th>Fecha Emision</th>
								<th>Guia Remision No.</th>
								<th class="hidden-mobile">Cliente</th>
								<th>Factura No.</th>
								<th>Destino</th>
								<th>Transportista</th>
								<th class="hidden-mobile">Placa</th>
								<th class="hidden-mobile">Codigo</th>
								<th>Producto</th>
								<th>Cantidad</th>
							</tr>	
						</thead>
						<tbody>
						<?php
							$dec=$dec->con_valor;
							$dcc=$dcc->con_valor;
							$t_cnt=0;
							if(!empty($guias)){
								$n=0;

								$grup='';
								foreach ($guias as $guia) {
									$n++;
									if($guia->fac_fecha_emision=='1990-01-01'){
										$guia->fac_fecha_emision='';
									}
									if($grup!=$guia->gui_id){
						?>
								<tr>
									<td class="hidden-mobile"><?php echo $n?></td>
									<td><?php echo $guia->gui_fecha_emision?></td>
									<td><?php echo $guia->gui_numero?></td>
									<td class="hidden-mobile"><?php echo $guia->gui_nombre?></td>
									<td><?php echo $guia->fac_numero?></td>
									<td><?php echo $guia->gui_destino?></td>
									<td><?php echo $guia->tra_razon_social?></td>
									<td class="hidden-mobile"><?php echo $guia->tra_placa?></td>
						<?php
									}else{

						?>	
									<td></td>	
									<td></td>	
									<td></td>	
									<td></td>	
									<td></td>	
									<td class="hidden-mobile"></td>	
									<td class="hidden-mobile"></td>	
									<td class="hidden-mobile"></td>	
						<?php
									}
						?>			
									<td class="hidden-mobile"><?php echo $guia->mp_c?></td>
									<td><?php echo $guia->mp_d?></td>
									<td class="number"><?php echo number_format($guia->dtg_cantidad,$dec)?></td>
								</tr>

						<?php	
								$grup=$guia->gui_id;
								$t_cnt+=round($guia->dtg_cantidad,$dec);
								
								}
							}
						?>
						<tr class="total">
								<th>Total</th>
								<th class="hidden-mobile"></th>
								<th class="hidden-mobile"></th>
								<th ></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th class="hidden-mobile"></th>
								<th class="hidden-mobile"></th>
								<th class="number"><?php echo number_format($t_cnt,$dec)?></th>
								
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

	window.onload = function () {

      var mensaje ='<?php echo $mensaje;?>';
      if(mensaje != '')
      {

        swal("", mensaje, "info");
      }
      
    }
    
</script>