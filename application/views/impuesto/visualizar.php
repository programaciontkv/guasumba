<?php
	switch ($impuesto->por_siglas) {
									case "IR": $tipo="IMPUESTO A LA RENTA"; break;
									case "IV": $tipo="IVA"; break;
				                    case "IC": $tipo="ICE"; break;
				                    case "IRB": $tipo="IRBPN"; break;
				                    case "ID": $tipo="SALIDA DIVISAS"; break;
				                }
?>				                
<p><strong>Tipo:</strong> <?php echo $tipo?></p>
<p><strong>Codigo:</strong> <?php echo $impuesto->por_codigo?></p>
<p><strong>Codigo Ats:</strong> <?php echo $impuesto->por_cod_ats?></p>
<p><strong>Descripcion:</strong> <?php echo $impuesto->por_descripcion?></p>
<p><strong>Codigo Cuenta:</strong> <?php echo $impuesto->pln_codigo?></p>
<p><strong>Descripcion Cuenta:</strong> <?php echo $impuesto->pln_descripcion?></p>
<p><strong>Porcentaje:</strong> <?php echo $impuesto->por_porcentage?></p>

