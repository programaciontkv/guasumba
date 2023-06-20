<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>rep_ctas_cobrar/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
    <!-- <form id="exp_pdf" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>inventario/show_frame/<?php echo $permisos->opc_id?>">
        	<input type="button" value="REPORTE" class="btn btn-warning"  onclick="validar()" />
        	<div style="display: none;">
	        	<input type="text" id='txt2' name='txt2' class="form-control" style="width: 180px" value='<?php echo $txt?>'/>
	        	<input type="date" id='fecha' name='fecha' class="form-control" style="width: 150px" value='<?php echo $fec2?>' />
	        	<input type="checkbox" id='familia2' name='familia2' <?php echo $fam?> onclick="inicio()"/>
				<input type="checkbox" id='tip2' name='tip2'  <?php echo $tip?>/>
				<input type="checkbox" id='detalle2' name='detalle2'  <?php echo $det?> />
			</div>
       	</form> -->   	
      <h1>
        Reporte de cuentas por cobrar
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-8">
					<form action="<?php echo $buscar;?>" method="post" id="frm_buscar">
						
					<table width="100%">
						<tr>
							<td><label>Buscar:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							
							<td>
								<input type="checkbox" id='vencer'  name='vencer' <?php echo $vencer?> /><label>Ctas vencer</label>
								<input type="checkbox" id='vencido'   name='vencido'  <?php echo $vencido?> /><label>Ctas vencidas</label>
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
					<table id="tbl_list" class="table table-bordered table-list table-striped" width="100%">
													<thead>
                <tr>
                	<th></th>
                    <th colspan="5">Documento</th>
                    <th ><?php echo $leyenda1 ?></th>
                    <th colspan="6" style="text-align:center;"><?php echo $leyenda2 ?></th>
                    <th ></th>
                </tr>
                <tr>
                	<th></th>
                    <th>Cliente</th>
                    <th># Documento</th>
                    <th>Fecha Emision</th>
                    <th>Fecha Vencimiento</th>
                    <th>Total Factura</th>
                    <th>Total</th>
                    <!-- <th>30 Dias</th>
                    <th>60 Dias</th>
                    <th>90 Dias</th>
                    <th>120 Dias</th>
                    <th>>120 Dias</th> -->
                   
                    <?php 
                    if (!empty($creditos)) {
                    	// code...
                    foreach($creditos as $cred)
                    {
                    	?>
                    <th><?php echo $cred->cre_descripcion ?></th>
                    <?php 
                    $dias =$cred->cre_descripcion;
                    }
                    ?>
                    <th>A > <?php echo $dias ?></th>
                    <?php
                  }
                    ?>
                    
                    <th>Total</th>
                </tr>
            </thead>
						<tbody>
							<?php
							$dec=$dec->con_valor;
							$n = 0;
							$gr = '';
							$nombre='';
							$cantidad = count($creditos);
							$to_t     = array_fill(0, ($cantidad+1), '');
							$tcv_total= 0;
							 $tcv_vencer = 0;
							 $tcv_vencido = 0;
							if (!empty($documentos)) {
							
							foreach($documentos as $doc){
								
								
								 if( round($doc->total_g, 2) > 0)
								 {
								 	 $n++;
								 // print_r($doc->total_g.'<br>');
								 	 //$gr1     = $doc->fac_identificacion;
                        if ($doc->fac_identificacion != $gr && $n != 1) {
                            ?>
                            <tr class="tr <?php echo $gr ?>" id="tr_<?php echo $gr?>" style="height: 30px">
                                <th  ></th>
                                <th class="totales" colspan="2"></th>
                                <th class="totales" colspan="2">TOTAL <?php echo $nombre ?></th>
                                <th class="totales" align="right"><?php echo number_format($tcv_vencer, $dec) ?></th>
                                 <th class="totales" align="right"><?php echo number_format($tcv_vencido, 2) ?></th>
                               <?php
                              
                               foreach($to_t as $tv){
                               	?>
                              	<th class="totales" align="right"><?php echo number_format($tv,$dec) ?></th>
                                	<?php
                                }
                                ?>
                               
                                <th class="totales" align="right"><?php echo number_format($tcv_total, $dec) ?></th>
                            </tr>
                            <?php

                            $tcv_vencido = 0;
                            $tcv_vencer = 0;
                            $tcv_total= 0;
                            $to_t     = array_fill(0, ($cantidad+1), '');
                        }
                        ?>
									<?php 
								if ($doc->fac_identificacion != $gr ) {
									?>
									
									 <tr class="tr_t1" id="tr_t1<?php echo $gr?> "><td  lign="right" class="acordeon" onclick="mostrar(1,<?php echo $doc->fac_identificacion?>,this)">+</td>
									 	<td class="totales" lign="right" colspan="12" ><?php echo $doc->fac_nombre ?></td></tr>
									<?php 
								}else{
									?>
										
									<?php
								}
								?>
								<tr class="tr <?php echo $doc->fac_identificacion ?> tr_<?php echo $doc->fac_identificacion?>"  >
								<td></td>
								<td></td>
								<td><?php echo $doc->fac_numero ?></td>
								<td><?php echo $doc->fac_fecha_emision ?></td>
								<td><?php echo $doc->pag_fecha_v ?></td>
								<td><?php echo  number_format($doc->fac_total_valor, $dec) ?></td>
								<td><?php echo  number_format($doc->total_vencer, $dec) ?></td>
								<?php 
								$p=0;
								$i=0;
								$to=0;
								$tcv_total+=$doc->total_vencer;
								
		                  foreach($creditos as $cred)
		                  {
		                  	$p++;
		                  	$v ='total'.$p;
		                  	?>
		                  <td><?php  echo number_format($doc->$v, $dec) ?></td>

		                  <?php 
		                  $to+=$doc->$v;
		                  $tcv_total+=$doc->$v;
		                  $to_t[($i)]+= number_format($doc->$v, $dec);
		                  $i++;
		                  $v ='total'.($p+1);
		                  }
		                  // echo $i;
		                  $to_t[($i)]+= number_format($doc->$v, $dec);
		                  $tcv_total+=$doc->$v;
		                  $to+=$doc->$v;
		                  $to+=$doc->total_vencer;
		                   $tcv_vencido+=$doc->total_vencer;
		                  ?>
		                  <td><?php  echo number_format($doc->$v, $dec) ?></td>
		                 <td><?php  echo number_format($to, $dec) ?></td>
                </tr>
		                <?php
		                $gr     = $doc->fac_identificacion;
		                $nombre = $doc->fac_nombre;
		                $tcv_vencer+=$doc->fac_total_valor;


                 		 }
                 		}
                 	
										?>
										<tr class="tr <?php echo $gr ?>" id="tr_<?php echo $gr?>" style="height: 30px">
                                <th  ></th>
                                <th class="totales" colspan="2"></th>
                                <th class="totales" colspan="2">TOTAL <?php echo $nombre ?></th>
                                <th class="totales" align="right"><?php echo number_format($tcv_vencer, $dec) ?></th>
                                 <th class="totales" align="right"><?php echo number_format($tcv_vencido, 2) ?></th>
                               <?php
                              
                               foreach($to_t as $tv){
                               	?>
                              	<th class="totales" align="right"><?php echo number_format($tv,$dec) ?></th>
                                	<?php
                                }
                                ?>
                               
                                <th class="totales" align="right"><?php echo number_format($tcv_total, $dec) ?></th>
                            </tr>
                            <?php
                            }
                            ?>
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>

<style>
td{
	font-size: 16px !important;
}
.number{
	text-align: right !important;
}	

.tr_familia{
	background: #1B4D75 !important;
	color:#ffffff !important;
	font-weight: bolder !important;
	height: 15px !important;
}	

td{
	padding-top: 0px !important;
	padding-bottom: 0px !important;

}
.totales{
	background: #2A76B5 !important;
	color:#ffffff !important;
	font-weight: bolder !important;
	height: 15px !important;
	padding-top: 0px !important;
	padding-bottom: 0px !important;
}


.tr_tipo{
	background: #2A76B5 !important;
	color:#ffffff !important;
	font-weight: bolder !important;
	height: 15px !important;
	padding-top: 0px !important;
	padding-bottom: 0px !important;
}
.tr_t1{
	background: #cddef2 !important;
	color:black !important;
	font-weight: bolder !important;
	height: 15px !important;
	padding-top: 0px !important;
	padding-bottom: 0px !important;
}
.tr_t2{
	background: #cddef2 !important;
	color:black !important;
	font-weight: bolder !important;
	height: 15px !important;
	padding-top: 0px !important;
	padding-bottom: 0px !important;
}

.total{
	background: #081B38 !important;
	color:#ffffff !important;
	font-weight: bolder !important;
}

.acordeon{
	font-size: 18px !important;
	text-align: center !important;
}

tr{
	height: 25px !important;
}
</style>

<script type="text/javascript">

	window.onload = function () {

      var mensaje ='<?php echo $mensaje;?>';
      if(mensaje != '')
      {

        swal("", mensaje, "info");
      }
      inicio();
      
    }
	
	function inicio(){
		
		$('.tr ').hide();
		$('.tb').html('-');
	
	}
	function mostrar(opc, id,obj){

		if(opc==1){
			if($(obj).html()=='+'){
					$(obj).html('-');
					$('.tr_'+id).show();
					$('#tr_'+id).show();
				
			}else{
				$(obj).html('+');
					$('#tr_'+id).hide();
					$('.tr_'+id).hide();
			}
		}
	}

	function validar(){
		if(parseFloat($('#total_inv').html())!=0){
			$('#exp_pdf').submit();
		}
	}

	

var checkbox1 = document.getElementById('vencer');
var checkbox2 = document.getElementById('vencido');
checkbox1.addEventListener("click", validaCheckbox, false);
checkbox2.addEventListener("click", validaCheckbox2, false);
function validaCheckbox()
{
  var checked1 = checkbox1.checked;
  if(checked1){
    document.getElementById("vencido").checked = false;
  }
}

function validaCheckbox2()
{
  var checked2 = checkbox2.checked;
  if(checked2){
   document.getElementById("vencer").checked = false;
  }

}



</script>
</section>

