<?php
$tipo = $banco_caja->byc_tipo;
$tipo_cuenta = $banco_caja->byc_tipo_cuenta;

if($tipo==0){
	$tipo='Banco';
}
if($tipo==1){
	$tipo='Caja';
}
if($tipo==2){
	$tipo='Caja Chica';
}

if($tipo_cuenta==0){
	$tipo_cuenta='Corriente';
}
if($tipo_cuenta==1){
	$tipo_cuenta='Ahorros';
}


?>

<p><strong>REFERENCIA:</strong> <?php echo $banco_caja->byc_referencia?></p>
<p><strong>TIPO:</strong> <?php echo $tipo?></p>
<p><strong>CUENTA:</strong> <?php echo $banco_caja->byc_num_cuenta?></p>
<p><strong>TIPO DE CUENTA:</strong> <?php echo $tipo_cuenta?></p>
<p><strong>SALDO:</strong> <?php echo $banco_caja->byc_saldo ?></p>
<p><strong>CUENTA CONTABLE:</strong> <?php echo $banco_caja->byc_cuenta_contable ?></p>
<p><strong>DESCRIPCIÓN DE LA CUENTA CONTABLE: </strong>   <?php echo $banco_caja->pln_descripcion ?></p>



