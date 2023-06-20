<section class="content-header">
	  	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>rep_ventas_por_producto/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
       	<h1>
        Ventas por Productos
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
							<td hidden><label>Empresa:</label></td>
							<td hidden>
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
							<td><label>Tipo:</label></td>
							<td>
								<select name="tipo" id="tipo" class="form-control" style="width: 200px">
									<option value="26">PRODUCTO TERMINADO</option>
									<option value="69">MATERIA PRIMA</option>
									<option value="79">SERVICIOS</option>
									<option value="80">OTROS</option>
								</select>
								<script>
									var tp="<?php echo $ids?>";
									tipo.value=tp;
								</script>
							</td>
							<td><label>Bucar:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 150px" value='<?php echo $txt?>' placeholder='CODIGO/DESCRIPCION'/></td>
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control" style="width: 150px" value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control" style="width: 150px" value='<?php echo $fec2?>' /></td>
							<td>
								<!-- <button class="btn btn-info" onclick="buscar()"><span class="fa fa-search"></span> Buscar</button> -->
								<input type="button" value="Buscar" onclick="buscar()" class="btn btn-info">
								</td>
						</tr>
					</table>
					</form>
				</div>			
			</div>
			<br>
			<div class="row">
				<div class="col-md-12" id="detalle">
					<?php echo $detalle?>
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

.number{
	text-align: right;
}
	
</style>
<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>/assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>/assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script src="<?php echo base_url(); ?>/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/bower_components/accounting/accounting.js"></script>
<script>

	var base_url='<?php echo base_url();?>';
	var dec=2;
	
	function round(value, decimals) {
		return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
	}

	function buscar(){

		$.ajax({
                      beforeSend: function () {
                      },
                      url: base_url+"rep_ventas_por_producto/buscar/"+$('#empresa').val()+"/"+$('#fec1').val()+"/"+$('#fec2').val()+"/"+$('#tipo').val()+"/"+$('#txt').val(),
                      type: 'JSON',
                      dataType: 'JSON',
                      success: function (dt) {
                            $('#detalle').html(dt.detalle);
                          	calculos();
                      }
                    });
	}

	function calculos(){
		var n = 0;
		
		$('.enc').each(function () {
			n++;
			tc = 0;
			$('.cnt'+n).each(function () {
				tc+= round($(this).html().replace(/,/g, ''),dec);
			});
			vc= accounting.formatMoney(tc, '', dec, ',', '.');
			$('#tv_cnt' + n).html(vc);

			tv = 0;
			$('.val'+n).each(function () {
				tv+= round($(this).html().replace(/,/g, ''),dec);
			});
			vv= accounting.formatMoney(tv, '', dec, ',', '.');
			$('#tv_val' + n).html(vv);

		});

		var thc=0;
		$('.th_cnt').each(function () {
			thc+= round($(this).html().replace(/,/g, ''),dec);
		});
		hc= accounting.formatMoney(thc, '', dec, ',', '.');
		$('#tv_cnt').html(hc);

		var thv=0;
		$('.th_val').each(function () {
			thv+= round($(this).html().replace(/,/g, ''),dec);
		});
		hv= accounting.formatMoney(thv, '', dec, ',', '.');
		$('#tv_val').html(hv);

	}
</script>
									

									

