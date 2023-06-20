<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
	
      <h1>
        Generar Anexo Transaccional Simplificado
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				
				<div class="col-md-12">
					<form action="<?php echo base_url().'ats/generar_ats/'.$permisos->opc_id?>" method="post" id="frm_search">
						
					<table style="margin-left:-10px">
						<tr>
		                  	<td >
								<label>Año:</label>
							</td>
		                  	<td >
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
		                  	</td>
		                  	<td >
								<label>Mes:</label>
							</td>
		                  	<td >
		                  		<select class="form-control"  aria-describedby="sizing-addon2" id='mes' name='mes'>
		                            <option value="01">Enero</option>
		                            <option value="02">Febrero</option>
		                            <option value="03">Marzo</option>
		                            <option value="04">Abril</option>
		                            <option value="05">Mayo</option>
		                            <option value="06">Junio</option>
		                            <option value="07">Julio</option>
		                            <option value="08">Agosto</option>
		                            <option value="09">Septiembre</option>
		                            <option value="10">Octubre</option>
		                            <option value="11">Noviembre</option>
		                            <option value="12">Diciembre</option>
		                      </select>
		                  	</td>
							
							<td><button type="buttom" class="btn btn-danger" onclick="validar()"> Descargar XML</button>
								</td>
						</tr>
					</table>
					<script type="text/javascript">
		                anio.value='<?php echo $anio?>';
		                mes.value='<?php echo $mes?>';
		            </script>
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

<script type="text/javascript">
	function validar(){
        if($('#anio').val()==''){
            alert('Seleccione anio');
        } else if($('#mes').val()==''){
            alert('Seleccione mes');
        } else{
            $('#frm_search').submit();    
        }
        
    }
</script>
