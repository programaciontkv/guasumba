<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>inventario/excel/<?php echo $permisos->opc_id?>/<?php echo $fec2?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
        <input type="button" value="REPORTE" class="btn btn-warning"  onclick="validar()" style="float:right;"/>
        	
      <h1>
        Inventario
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
							<td hidden>
								<select name="tipo" id="tipo" class="form-control" style="width: 200px">
									<option value="26">PRODUCTO TERMINADO</option>
								</select>
								<script>
									var tp="<?php echo $ids?>";
									tipo.value=tp;
								</script>
							</td>
							<td><label>Al:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control" style="width: 150px" value='<?php echo $fec2?>' /></td>
							<td>
								<input type="checkbox" id='familia' name='familia' <?php echo $fam?> onclick="inicio()"/><label>Familia</label>
								<input type="checkbox" id='tip' name='tip'  <?php echo $tip?> onclick="inicio()"/><label>Tipo</label>
								<input type="checkbox" id='detalle' name='detalle'  <?php echo $det?> onclick="inicio()"/><label>Detalle</label>
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
								<th>Código</th>
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
						$inv_a=0;
						$grup='';
						$nom_a='';
						$inv_b=0;
						$inv_b1=0;
						$grup_b='';
						$nom_b='';
						$nom_b1='';
						if(!empty($inventarios)){
							foreach ($inventarios as $inventario) {
								$n++;
								$inv=round($inventario->ingresos,$dcc)-round($inventario->egresos,$dcc);
								$tot+=$inv;   

							$nom_a=$inventario->familia;
							$nom_b=$inventario->tipo;

							if($inventario->mp_b!=$grup_b && $n!=1){
								// if(round($inv_b,$dcc)!=0){	
									?>
								<tr  class="tr_t2"  id="tr_t2<?php echo $grup_b?>">
								<td colspan="3" ></td>
								<td >TOTAL  <?php echo $nom_b1?> </td>
								<td  class="number"><?php echo str_replace(',','', number_format($inv_b,$dcc))?></td>
							</tr>
						<?php
							 // }
							$inv_b=0;
							}
								//// total y familias
							if($inventario->mp_a != $grup && $n!=1 ){
						?>
								<tr class="tr_t1" id="tr_t1<?php echo $grup?>">
									<td colspan="3" id="<?php echo 'a_'.$grup?>"  class="acordeon" onclick="mostrar(1,<?php echo $grup?>,this)"></td>
									<td  style="mso-number-format:'@'">TOTAL  <?php echo $nom_a1?> </td>
									<td class="number"><?php echo str_replace(',','', number_format($inv_a,$dcc))?></td>
								</tr>
						<?php
							 $inv_a=0;
							  }

								 if($inventario->mp_a != $grup){
									?>
								<tr class="tr_familia">
									<td id="<?php echo 'a_'.$grup?>"  lang="<?php echo $n?>"class="acordeon" onclick="mostrar(1,<?php echo $inventario->mp_a?>,this)">-</td>
									<td colspan="4" style="mso-number-format:'@'"><?php echo $nom_a?></td>
								</tr>
								<?php
							 $inv_a=0;
							  }
							  	
							
							if($inventario->mp_b!=$grup_b ){
								// if(round($inv_b,$dcc)!=0){	
						?>
								<tr class="tr_tipo <?php echo 'tra_'.$inventario->mp_a?>">
									<td id="<?php echo 'b_'.$grup_b?>" lang="<?php echo $n?>" class="acordeon <?php echo 'tda_'.$grup?> tb" onclick="mostrar(0,<?php echo $inventario->mp_b?>,this)">-</td>
									<td  colspan="4" style="mso-number-format:'@'"><?php echo $nom_b?></td>
								</tr>
						<?php
							 // }
							$inv_b=0;
							}

							
							//// productos
							if(round($inv,$dcc)!=0){	
						?>
							<tr class="tr <?php echo 'b_'.$inventario->mp_b ?> <?php echo 'a_'.$inventario->mp_a ?>  ">
								<td></td>
								<td style="mso-number-format:'@'"><?php echo $inventario->mp_c?></td>
								<td><?php echo $inventario->mp_d?></td>
								<td><?php echo $inventario->mp_q?></td>
								<td class="number"><?php echo str_replace(',','', number_format($inv,$dcc))?></td>
							</tr>
						<?php
							}

							$grup=$inventario->mp_a;
							$grup_b=$inventario->mp_b;
							$inv_a+=round($inv,$dcc);
							$nom_a=$inventario->familia;
							$nom_a1=$inventario->familia;
							$nom_b1=$inventario->tipo;
							$inv_b+=round($inv,$dcc);
							

								$inv_b1+=round($inv,$dcc);
							$nom_b1=$inventario->tipo;
							}
						}
						if(round($inv_b1,$dcc)!=0){	
						?>
							<tr  class="tr_t2"  id="tr_t2<?php echo $grup_b?>">
								<td colspan="3" ></td>
								<td >TOTAL  <?php echo $nom_b1?> </td>
								<td  class="number"><?php echo str_replace(',','', number_format($inv_b,$dcc))?></td>
							</tr>
						<?php
						}
						if(round($inv_a,$dcc)!=0){		
						?>	
						<!-- 	<tr class="tr_familia">
								<td id="<?php echo 'a_'.$grup?>" class="acordeon" onclick="mostrar(1,<?php echo $grup?>,this)"></td>
								<td style="mso-number-format:'@'"></td>
								<td></td>
								<td>TOTAL</td>
								<td class="number"><?php echo str_replace(',','', number_format($inv_a,$dcc))?></td>
							</tr> -->
						<?php
						}	
						if(round($inv_a,$dcc)!=0){	
						?>
								<tr class="tr_t1">
									<td colspan="3" id="<?php echo 'a_'.$grup?>" class="acordeon" onclick="mostrar(1,<?php echo $grup?>,this)"></td>
									<td  style="mso-number-format:'@'">TOTAL <?php echo $nom_a1?>  </td>
									<td class="number"><?php echo str_replace(',','', number_format($inv_a,$dcc))?></td>
								</tr>
						<?php
							 $inv_a=0;
							  }
						?>	
							<tr class="total">
								<td colspan="3"></td>
								<td>TOTALES</td>	
								<td class="number" id="total_inv"><?php echo str_replace(',','', number_format($tot,$dcc))?></td>
							</tr>
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
      
    }
	
	function inicio(){
		if($('#detalle').prop('checked')==true){
			$('.tr').show();
			$('.tb').html('-');
		}else{
			$('.tr').hide();
			$('.tb').html('+');
		}

		if($('#tip').prop('checked')==true){
			$('.tr_tipo').show();
			$('.tr_t2').show();
			

		}else{
			$('.tr_tipo').hide();
			$('.tr_t2').hide();
		}

		if($('#familia').prop('checked')==true){
			$('.tr_familia').show();
			$('.tr_t1').show();
		}else{
			$('.tr_familia').hide();
			 $('.tr_t1').hide();
		}
	}
	function mostrar(opc, id,obj){
		if(opc==0){
			if($(obj).html()=='+'){
				$(obj).html('-');
				if($('#detalle').prop('checked')==true){
					$('.b_'+id).show();
					 $('#tr_t2'+id).show();
				}
			}else{

				$(obj).html('+');
				$('.b_'+id).hide();
				$('#tr_t2'+id).hide();
			}
		}

		if(opc==1){
			if($(obj).html()=='+'){
				$(obj).html('-');
				if($('#tip').prop('checked')==true){
					$('.tra_'+id).show();
					$('.tda_'+id).html('+');
					$('#tr_t1'+id).show();
				}else{
					if($('#detalle').prop('checked')==true){
						$('.a_'+id).show();
						$('#tr_t1'+id).show();
					}
				}
			}else{
				$(obj).html('+');
				$('.a_'+id).hide();
				$('.tra_'+id).hide();
				$('#tr_t1'+id).hide();
				$('.tr_t2').hide();
			}
		}
	}

	function validar(){
		if(parseFloat($('#total_inv').html())!=0){
			url="<?php echo base_url();?>inventario/show_frame/<?php echo $permisos->opc_id?>";
			$('#frm_buscar').attr('action',url);
			$('#frm_buscar').submit();
		}
	}

</script>
</section>

