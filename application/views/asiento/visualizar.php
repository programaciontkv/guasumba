<?php
if($cuenta->pln_tipo==0){
	$tipo='SUMATORIA';
}else{
	$tipo='MOVIMIENTO';
}

if($cuenta->pln_operacion==0){
	$operacion='SUMA';
}else{
	$operacion='RESTA';
}
?>
<p><strong>Codigo:</strong> <?php echo $cuenta->pln_codigo?></p>
<p><strong>Descripcion:</strong> <?php echo $cuenta->pln_descripcion?></p>
<p><strong>Tipo:</strong> <?php echo $tipo?></p>
<p><strong>Operacion:</strong> <?php echo $operacion?></p>
<p><strong>Observaciones:</strong> <?php echo $cuenta->pln_obs?></p>
<p><strong>Estado:</strong> <?php echo $cuenta->est_descripcion?></p>
