<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
	
      <h1>
        Reportes Contables
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				
				<div class="col-md-8">
					<form action="" method="post" id="frm_search">
						
					<table style="margin-left:-10px">
						<tr>
							<td class="hidden-mobile" ><label>Reporte:</label></td>
							<td class="hidden-mobile"  	>
								<select class="form-control" id='reporte' name='reporte' onchange="mostrar_ocultar(this)">
		                          <option value="">SELECCIONE</option>
		                          <option value="0">DIARIO GENERAL</option>
		                          <option value="1">LIBRO MAYOR</option>
		                          <option value="2">BALANCE DE COMPROBACION</option>
		                          <option value="3">BALANCE GENERAL</option>
		                          <option value="4">ESTADO DE PERDIDAS Y GANANCIAS</option>
		                      </select>
                  			</td>
                  			<td class="cont_cuenta">
                  				<label>Cuenta:</label>
                  			</td>
                  			<td class="cont_cuenta">
                  				<input type="text" id='cuenta' name='cuenta' class="form-control" list="list_cuentas"/>
                  			</td>
							<td class="cont_nivel">
								<label>Nivel:</label>
							</td>
							<td class="cont_nivel">
								<select class="form-control" id='nivel' name='nivel'>
		                            <option value="1">Nivel 1</option>
		                            <option value="2">Nivel 2</option>
		                            <option value="3">Nivel 3</option>
		                            <option value="4">Nivel 4</option>
		                            <option value="5">Nivel 5</option>
		                      </select>
		                  	</td>
		                  	<td class="cont_periodo">
								<label>Año:</label>
							</td>
		                  	<td class="cont_periodo">
		                  		<select class="form-control"  aria-describedby="sizing-addon2" id='anio' name='anio'>
		                            <option value="2015">2015</option>
		                            <option value="2016">2016</option>
		                            <option value="2017">2017</option>
		                            <option value="2018">2018</option>
		                            <option value="2019">2019</option>
		                            <option value="2020">2020</option>
		                            <option value="2021">2021</option>
		                            <option value="2022">2022</option>
		                            <option value="2023">2023</option>
		                            <option value="2024">2024</option>
		                            <option value="2025">2025</option>
		                            <option value="2026">2026</option>
		                            <option value="2027">2027</option>
		                            <option value="2028">2028</option>
		                            <option value="2029">2029</option>
		                            <option value="2030">2030</option>
		                        
		                      </select>
		                      <script type="text/javascript">
		                          var a='<?php echo $anio?>';
		                          anio.value=a;
		                      </script>
		                  	</td>
		                  	<td class="cont_periodo">
								<label>Mes:</label>
							</td>
		                  	<td class="cont_periodo">
		                  		<select class="form-control"  aria-describedby="sizing-addon2" id='mes' name='mes'>
		                            <option value="1">Enero</option>
		                            <option value="2">Febrero</option>
		                            <option value="3">Marzo</option>
		                            <option value="4">Abril</option>
		                            <option value="5">Mayo</option>
		                            <option value="6">Junio</option>
		                            <option value="7">Julio</option>
		                            <option value="8">Agosto</option>
		                            <option value="9">Septiembre</option>
		                            <option value="10">Octubre</option>
		                            <option value="11">Noviembre</option>
		                            <option value="12">Diciembre</option>
		                            <option value="13">Anual</option>
		                      </select>
		                  	</td>
							<td class="cont_fecha"><label>Desde:</label></td>
							<td class="cont_fecha"><input type="date" id='fec1' name='fec1' class="form-control"  value='<?php echo $fec1?>' /></td>
							<td class="cont_fecha"><label>Hasta:</label></td>
							<td class="cont_fecha"><input type="date" id='fec2' name='fec2' class="form-control"  value='<?php echo $fec2?>' /></td>
							<td><button type="button" class="btn btn-danger" onclick="validar()"> PDF</button>
								</td>
							<td><button type="button" class="btn btn-success" onclick="excel()"> EXCEL</button>
								</td>	
						</tr>
					</table>
					</form>
				</div>			
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-list table-hover" style="margin-left:-22px">
						
					</table>
				</div>	
			</div>
		</div>
	</div>


</section>
<datalist id="list_cuentas">
	<?php
	if(!empty($cuentas)){
		foreach ($cuentas as $cta) {
	?>	
	<option value="<?php echo $cta->pln_codigo?>"><?php echo $cta->pln_codigo.' '.$cta->pln_descripcion?></option>
	<?php	
		}
	}
	?>
</datalist>

<script type="text/javascript">
	var buscar='<?php echo $buscar;?>';
	var exc='<?php echo $excel;?>';

	$( document ).ready(function() {
         mostrar_ocultar();
    });
   function mostrar_ocultar() {

                switch ($('#reporte').val()) {
                    case '0':
                        $('.cont_nivel').hide();
                        $('.cont_periodo').hide();
                        $('.cont_fecha').show();
                        $('.cont_cuenta').hide();
                        $('#cuenta').val('');
                        $('#search').hide();
                        $('#thlista').hide();
                        $('#tblista').html('');
                        break;
                    case '1':
                        $('.cont_nivel').hide();
                        $('.cont_periodo').hide();
                        $('.cont_fecha').show();
                        $('.cont_cuenta').show();
                        $('#search').show();
                        $('#thlista').show();
                        break;
                    case '2':///balance de comprobacion
                        $('.cont_nivel').show();
                        $('.cont_periodo').show();
                        $('.cont_fecha').hide();
                        $('.cont_cuenta').hide();
                        $('#cuenta').val('');
                        $('#search').hide();
                        $('#thlista').hide();
                        $('#tblista').html('');
                        break;
                    case '3'://balance genera
                        $('.cont_nivel').show();
                        $('.cont_periodo').show();
                        $('.cont_fecha').hide();
                        $('.cont_cuenta').hide();
                        $('#cuenta').val('');
                        $('#search').hide();
                        $('#thlista').hide();
                        $('#tblista').html('');
                        break;
                    case '4':
                        $('.cont_nivel').show();
                        $('.cont_periodo').show();
                        $('.cont_fecha').hide();
                        $('.cont_cuenta').hide();
                        $('#cuenta').val('');
                        $('#search').hide();
                        $('#thlista').hide();
                        $('#tblista').html('');
                        checked_fecha();
                        break;
                    default :
                        $('.cont_nivel').hide();
                        $('.cont_periodo').hide();
                        $('.cont_fecha').show();
                        $('.cont_cuenta').hide();
                        $('#cuenta').val('');
                        $('#search').hide();
                        $('#thlista').hide();
                        $('#tblista').html('');
                        break;
                }
            }

    function validar(){
        if($('#reporte').val()==''){
            alert('Seleccione un Reporte');
        }else{
        	$('#frm_search').attr("action",buscar);   
            $('#frm_search').submit();    
        }
        
    }

    function excel() {
    	if($('#reporte').val()==''){
            alert('Seleccione un Reporte');
        }else{
        	$('#frm_search').attr("action",exc);   
            $('#frm_search').submit();    
        }
    	
    }
	
</script>