
<section class="content">
	<h1>
        Registro de Facturas <?php echo $titulo?>
      </h1>
	
					<table width="100%" border="1">
						<thead>
							<th>Fecha</th>
							<th>Tipo</th>
							<th>Documento</th>
							<th>Ruc</th>
							<th>Proveedor</th>
							<th>Concepto</th>
							<th>Total Valor</th>
							<th>Estado</th>
						</thead>
						<tbody>
						<?php 
					
						if(!empty($facturas)){
							$dec=$dec->con_valor;
							foreach($facturas as $factura) {

								if($factura->reg_estado==7){
									$style="background:$factura->est_color";
								}else{
									$style="";				
								}
						?>
							<tr style="<?php echo $style;?>">
								<td ><?php echo $factura->reg_femision?></td>
								<td ><?php echo $factura->tdc_descripcion?></td>
								<td ><?php echo $factura->reg_num_documento?></td>
								<td ><?php echo $factura->cli_ced_ruc?></td>
								<td ><?php echo $factura->cli_raz_social?></td>
								<td ><?php echo $factura->reg_concepto?></td>
								<td style="text-align: right;"><?php echo number_format($factura->reg_total,$dec)?></td>

								<td ><?php echo $factura->est_descripcion?></td>
								
							</tr>
						<?php
							}
						}
						?>
						</tbody>
					</table>
			
</section>
 <script type="text/javascript">

    
    window.onload = function () {
      window.print();
    }
    
</script> 
 
