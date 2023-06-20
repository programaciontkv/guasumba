<?php
$tipo = $bancos_tarjetas->btr_tipo;

if($tipo==0){
	$t='BANCO';
}
if($tipo==1){
	$t='TARJETA';
}
if($tipo==2){
	$t='PLAZA';
}

switch ($bancos_tarjetas->btr_forma) {
case "0": $forma=""; break;
case "1": $forma="TARJETA DE CREDITO"; break;
case "2": $forma="TARJETA DE DEBITO"; break;
case "3": $forma="CHEQUE"; break;
case "4": $forma="EFECTIVO"; break;
case "5": $forma="CERTIFICADOS"; break;
case "6": $forma="TRANSFERENCIA"; break;
case "7": $forma="RETENCION"; break;
case "8": $forma="NOTA CREDITO"; break;
case "9": $forma="CREDITO"; break;
}
?>
<p><strong>TIPO:</strong> <?php echo $t?></p>
<p><strong>FORMA DE PAGO:</strong> <?php echo $forma?></p>
<p><strong>DESCRIPCION:</strong> <?php echo $bancos_tarjetas->btr_descripcion?></p>



