<p><strong>Categoria:</strong> <?php echo $tipo->cat_descripcion?></p>
<?php 
if($tipo->tps_relacion==1){
	$relacion="FAMILIA";
}else{
	$relacion="TIPO";
}
?>
<p><strong>Relacion:</strong> <?php echo $relacion?></p>
<p><strong>Familia:</strong> <?php echo $tipo->familia?></p>
<p><strong>Siglas:</strong> <?php echo $tipo->tps_siglas?></p>
<p><strong>Nombre:</strong> <?php echo $tipo->tps_nombre?></p>
<p><strong>Densidad:</strong> <?php echo number_format($tipo->tps_densidad,2)?></p>
<p><strong>Estado:</strong> <?php echo $tipo->est_descripcion?></p>

