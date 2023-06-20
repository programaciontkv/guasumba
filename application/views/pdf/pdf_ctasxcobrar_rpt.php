<section class="content-header">

	<table width="100%" style="margin-top:-40px;margin-left: -10px;margin-right: -10px;">
    <tr>
        <td colspan="3" width="100%" >
            <table class="encabezado3"  width="100%" >
                <tr><td><?php echo $empresa->emp_nombre; ?></td> 
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                <td rowspan="6" width="20%"><img src="<?php echo base_url().'imagenes/'.$empresa->emp_logo?>"  width="170px" height="110px"></td>
                 </tr>
                <tr><td><?php echo $empresa->emp_identificacion; ?> </td>  </tr>
                <tr><td><?php echo $empresa->emp_ciudad."-".$empresa->emp_pais; ?> </td>  </tr>
                <tr><td><?php echo "TELEFONO: " . $empresa->emp_telefono ?> </td>  </tr>
              
                
            </table>
        </td>
        <td></td>
    </tr>
    <tr>

    </tr>
  </table>
  <div class="row">
				<div class="col-md-12 titulo_p">
					<strong style="font-size: 20px;">CENTRAL DE COBRANZAS </strong>
				</div>
						
			</div>
 

	  

</section>
 

<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" border="1" class="table table-bordered table-list table-hover" width="100%">
						<tr>
							<th>No</th>
							<th>Factura</th>
							<th>Fecha Emision</th>
							<th>Fecha Vencimiento</th>
							<th>Total</th>
							<th>Pagado</th>
							<th>Saldo</th>
							<th>Estado</th>
							<!-- <th>Acciones</th> -->
						</tr>
						<tbody>
						<?php 
						$n=0;
						$fecha=date('Y-m-d');
						$grup="";
						$t_credito=0;
						$t_debito=0;
						$t_saldo=0;
						if(!empty($facturas)){
							foreach ($facturas as $factura) {
								$n++;
								$saldo=round($factura->fac_total_valor,$dec)-round($factura->pago,$dec);
								if($saldo>0 && $factura->pag_fecha_v>$fecha){
									$estado='POR VENCER';
								} else if($saldo>0 && $factura->pag_fecha_v<$fecha){
									$estado='VENCIDO';
								} else if($saldo<=0){
									$estado='PAGADO';
								}

								if($grup!=$factura->fac_identificacion && $n!=1){
						?>			
									<tr class='success'>
										<td class='total' colspan="3" style='font-weight: bolder;'></td>
										<td class='total' style='font-weight: bolder;'> TOTAL</td>
										<td class='number total' style='font-weight: bolder;'><?php echo str_replace(',','', number_format($t_credito,$dec))?></td>
										<td class='number total' style='font-weight: bolder;'><?php echo str_replace(',','', number_format($t_debito,$dec))?></td>
										<td class='number total' style='font-weight: bolder;'><?php echo str_replace(',','', number_format($t_saldo,$dec))?></td>
										<td class='total' colspan="2" style='font-weight: bolder;'> </td>
									</tr>
						<?php
								$t_credito=0;
								$t_debito=0;
								$t_saldo=0;				
								}
								
						?>
							
						<?php
							if($grup!=$factura->fac_identificacion){
						?>		
								
							<tr>
								<td></td>
								<td ><strong><?php echo $factura->fac_nombre?> </strong></td>
								<td style="mso-number-format:'@'"> <?php echo $factura->fac_identificacion?></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td>
									<!-- <a href="<?php echo base_url();?>ctasxcobrar/buscar_reporte/<?php echo $factura->fac_identificacion?>/<?php echo $permisos->opc_id?>/0" class="btn btn-danger" title="Reporte Mora"> Mora</a> -->
								</td>
								<!-- <td><a href="<?php echo base_url();?>ctasxcobrar/buscar_reporte/<?php echo $factura->fac_identificacion?>/<?php echo $permisos->opc_id?>/6" class="btn btn-danger" title="Reporte Estado de Cobros">Estado Cobros</a></td> -->
							</tr>
						<?php		
							}
						?>
						
							<tr <?php echo $estado?>>
								<td><?php echo $n?></td>				
								<td><?php echo $factura->fac_numero?></td>
								<td><?php echo $factura->fac_fecha_emision?></td>
								<td><?php echo $factura->pag_fecha_v?></td>
								<td class="number"><?php echo str_replace(',', '', number_format($factura->fac_total_valor,$dec))?></td>
								<td class="number"><?php echo str_replace(',', '', number_format($factura->pago,$dec))?></td>
								<td class="number"><?php echo str_replace(',', '', number_format($saldo,$dec))?></td>
								<td><?php echo $estado?></td>
								<!-- <td align="center">
									<div class="btn-group">
										<?php 
							        	if($permisos->rop_reporte){
										?>
											<a href="<?php echo base_url();?>ctasxcobrar/show_frame/<?php echo $factura->fac_id?>/<?php echo $permisos->opc_id?>" class="btn btn-danger" title="Detalle"> <span class="fa fa-file-pdf-o" ></span></a>
										<?php
										}	
										
									/*	if($permisos->rop_insertar){
										?>
												<a href="<?php echo base_url();?>ctasxcobrar/nuevo/<?php echo $opc_id?>/<?php echo $factura->fac_id?>" class="btn btn-primary" title="Pagos"> <span class="fa fa-edit" ></span></a>
										<?php 
											}*/
										?>
									</div>
								</td> -->
							</tr>
						<?php
								$grup=$factura->fac_identificacion;
								$t_credito+=round($factura->fac_total_valor,$dec);
								$t_debito+=round($factura->pago,$dec);
								$t_saldo+=round($saldo,$dec);
							}
						}
						?>
								<tr class='success'>
										<td class='total' colspan="3" style='font-weight: bolder;'></td>
										<td class='total' style='font-weight: bolder;'> TOTAL</td>
										<td class='number total' style='font-weight: bolder;'><?php echo str_replace(',','', number_format($t_credito,$dec))?></td>
										<td class='number total' style='font-weight: bolder;'><?php echo str_replace(',','', number_format($t_debito,$dec))?></td>
										<td class='number total' style='font-weight: bolder;'><?php echo str_replace(',','', number_format($t_saldo,$dec))?></td>
										<td class='total' colspan="2" style='font-weight: bolder;'> </td>
								</tr>
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>

</section>


<script type="text/javascript">

    
    window.onload = function () {
      window.print();
    }
    
</script>
<style type="text/css">

     *{
        font-size: 16px;
        font-family: "calibri", "normal";
        text-align: left;
    }                
    

    .numerico {
        text-align: right;
    }

    #encabezado1,#encabezado2, #encabezado3 {
        border: 1px solid;
        text-align: left;
    }

    #detalle {
        border: 1px solid;
        border-collapse: collapse;
    }

    #encabezado1 td, #encabezado1 th, #encabezado2 td, #encabezado2 th, #encabezado3 td, #encabezado3 th{
        text-align: left;
    }
    #detalle td, #detalle th{
        border: 1px solid;
        
    }

    #info td, #info th{
        border: none;
    }

    .titulo{
        font-size: 15px;
    }
     .sub_titulo{
        font-size: 18px;
    }
    .titulo_p{
        font-size: 19px;
        text-align: center;
        align-content: center;
        font-family: "calibri", "bold";
    }
    </style>